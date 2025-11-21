<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index');
    }

    public function listData()
    {

        $blogs = Blog::with('createdBy');

        return DataTables::of($blogs)
            ->addIndexColumn()
            ->addColumn('created_by', function ($blog) {
                return $blog->createdBy->name ?? "N/A";
            })
            ->addColumn('action', function ($row) {
                return view('blog.partials.actions', compact('row'))->render();
            })
            // ->editColumn('description', function ($blog) {
            //     return strip_tags($blog->description);
            // })
            ->editColumn('description', function ($blog) {
                return html_entity_decode(strip_tags($blog->description));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('blog.create');
    }



    public function store(Request $request)
    {
        $method = $request->method();

        $rules = [
            "title" => "required|string|min:3",
            "description" =>  "required|string",
        ];

        if ($method == 'POST') {
            $rules['banner'] = "required|image";
        }
        $request->validate($rules);

        if ($method == "POST") {
            $blog = new Blog();
        } else {
            $blog = Blog::find($request->blog_id);
        }

        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->user_id = Auth::id();
        $file = $request->banner;

        if ($request->has('banner')) {
            $blog->banner = $file->store('blog_banners', "public");
        }

        $blog->save();

        return redirect()->route(routePrefix() . 'blog.list')->withSuccess('Blog post is saved!');
    }

    public function edit($id)
    {
        $blog = Blog::where('id', $id)->findOrFail($id);
        return view('blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string',
            'banner' => 'nullable|image',
        ]);

        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->user_id = Auth::id();


        // If banner is deleted manually
        if (!$request->has('keep_media')) {
            if ($blog->banner && Storage::disk('public')->exists($blog->banner)) {
                Storage::disk('public')->delete($blog->banner);
            }
            $blog->banner = null;
        }

        //  If a new banner is uploaded
        if ($request->hasFile('banner')) {
            // Delete old banner if it exists
            if ($blog->banner && Storage::disk('public')->exists($blog->banner)) {
                Storage::disk('public')->delete($blog->banner);
            }

            $file = $request->file('banner');
            $blog->banner = $file->store('blog_banners', 'public');
        }

        $blog->save();

        return redirect()->route(routePrefix() . 'blog.list')->withSuccess('Blog updated successfully!');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.']);
        }

        // Delete banner file if exists
        if ($blog->banner && Storage::disk('public')->exists($blog->banner)) {
            Storage::disk('public')->delete($blog->banner);
        }

        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Blog deleted successfully!']);
    }
}
