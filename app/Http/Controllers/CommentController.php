<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Idea $idea)
    {
        // dd($idea);
        try {
            Comment::create([
                'idea_id' => $idea->id,
                'body' => $request->body
            ]);

            return response()->success('Comment created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->error('Failed to create comment', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Idea $idea, string $id)
    {
        $comment = Comment::find($id);
        if (auth()->user()->cannot('update', $comment)) {
            return response()->error('You are not allowed to update this comment', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $comment->update([
                'body' => $request->body
            ]);

            return response()->success('Comment updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->error('Failed to update comment', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);
        if (auth()->user()->cannot('delete', $comment)) {
            return response()->error('You are not allowed to delete this comment', Response::HTTP_UNAUTHORIZED);
        }
        try {
            $comment->delete();
            return response()->success('Comment deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->error('Failed to delete comment', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}