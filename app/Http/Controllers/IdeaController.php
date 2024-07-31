<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Http\Resources\IdeaResource;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ideas = Idea::with('user', 'category', 'status')->paginate(10);
        return response()->success(IdeaResource::collection($ideas));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {

        // dd($request->validated());
        // try {
        $idea = Idea::create($request->validated());
        return response()->success(new IdeaResource($idea));
        // } catch (\Exception $e) {
        //     return response()->error('Failed to create idea', Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        try {
            return response()->success(new IdeaResource($idea));
        } catch (ModelNotFoundException $e) {
            return response()->error('Idea not found', Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        try {
            $idea->update([
                'title' => $request->title ?? $idea->title,
                'description' => $request->description ?? $idea->description,
                'category_id' => $request->category_id ?? $idea->category_id,
            ]);
            return response()->success(new IdeaResource($idea));
        } catch (ModelNotFoundException $e) {
            return response()->error('Idea not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        try {
            $idea->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            return response()->error('Category not found', Response::HTTP_NOT_FOUND);
        }
    }
}