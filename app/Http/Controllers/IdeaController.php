<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use App\Http\Resources\IdeaResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreIdeaRequest;
use App\Mail\IdeaStatusUpdatedMailable;
use App\Http\Requests\UpdateIdeaRequest;
use App\Http\Requests\UpdateIdeaStatusRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $ideas = Idea::with('user', 'category', 'status')
            ->filter(request(['search', 'category', 'status', 'filter']))
            ->withCount('votes')
            ->latest()
            ->paginate(10);

        return response()->success(IdeaResource::collection($ideas));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {

        // dd($request->validated());
        try {
            $idea = Idea::create($request->validated());
            return response()->success(new IdeaResource($idea));
        } catch (\Exception $e) {
            return response()->error('Failed to create idea', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        if (auth()->user()->cannot('update', $idea)) {
            return response()->error('You are not allowed to update this idea', Response::HTTP_UNAUTHORIZED);
        }
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
        if (auth()->user()->cannot('delete', $idea)) {
            return response()->error('You are not allowed to update this idea', Response::HTTP_UNAUTHORIZED);
        }
        try {
            $idea->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            return response()->error('Category not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function updateStatus(UpdateIdeaStatusRequest $request, Idea $idea)
    {
        // dd($request->all());
        if (auth()->user()->cannot('update', $idea)) {
            return response()->error('You are not allowed to update this idea', Response::HTTP_UNAUTHORIZED);
        }
        try {
            $idea->status_id = $request->input('status_id');
            $idea->save();

            if ($request->input('notify') == 'yes') {
                $this->notifyAllVoters($idea);
            }

            return response()->json(['message' => 'Status updated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->error('Idea not found', Response::HTTP_NOT_FOUND);
        }
    }

    private function notifyAllVoters(Idea $idea)
    {
        $idea->votes()
            ->select('name', 'email')
            ->chunk(100, function ($voters) use ($idea) {
                foreach ($voters as $voter) {
                    Mail::to($voter)
                        ->queue(new IdeaStatusUpdatedMailable($idea));
                }
            });
    }
}
