<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        // No need to fetch all categories for server-side DataTable
        return view('category.index'); // Blade will initialize DataTable via AJAX
    }


    public function categoryData(Request $request)
    {
        $categories = EventCategory::query();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('category.partials.actions', compact('row'))->render();
            })
            ->rawColumns(['action']) // allow HTML
            ->make(true);
    }



    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
        ]);

        EventCategory::create([
            'name' => $request->name
        ]);

        return redirect()->route(routePrefix().'category.index')->withSuccess('Added!');
    }

    // Delete category
    public function destroy(EventCategory $category)
    {
        try {
            $category->delete();
            return response()->json(['status' => true, 'message' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Unable to delete category.']);
        }
    }
}
