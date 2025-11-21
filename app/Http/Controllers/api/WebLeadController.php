<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebLeadController extends Controller
{
    public function contactLead(Request $request)
    {
        $request->validate([
            "name" => "required|string|min:3,max:20",
            "email" => "required|email",
            "phone" => "nullable|digits:10",
            "body" => "required|string|min:5"
        ]);

        DB::table('contact_leads')->insert($request->all());

        return response()->json([
            "status" => true,
            "message" => "Contact lead is sent"
        ]);
    }

    public function blogs(Request $request)
    {

        $blogs = Blog::with('createdBy')->latest();
        if ($request->has('limit')) {
            $blogs = $blogs->paginate(9);
        } else {
            $blogs = $blogs->paginate(10);
        }
        return BlogResource::collection($blogs)->additional(['status' => true]);
    }

    public function blogInfo($id)
    {
        $blog = Blog::find($id);
        return response()->json([
            "status" => true,
            "data" => new BlogResource($blog)
        ]);
    }
}
