<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select('id', 'name', 'slug', 'description')->paginate(10);
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return new CategoryResource($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return new CategoryResource($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return new CategoryResource($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // dd($category);
        try {
            $category->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
