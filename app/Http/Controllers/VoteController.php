<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Idea $idea)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($idea->isVotedByUser()) {
            $idea->removeVote();
            return response()->json(['message' => 'Vote removed successfully']);
        } else {
            $idea->vote();
            return response()->json(['message' => 'Vote recorded successfully']);
        }
    }
}