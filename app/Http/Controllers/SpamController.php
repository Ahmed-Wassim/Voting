<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpamController extends Controller
{
    public function markAsSpam(Idea $idea)
    {

        try {
            $idea->spams++;
            $idea->save();
            return response()->success(['Message' => 'The Idea Marked As Spam']);
        } catch (ModelNotFoundException $e) {
            return response()->error('Idea not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function markAsNotSpam(Idea $idea)
    {
        if (auth()->guest() || !auth()->user()->isAdmin()) {
            return response()->error('You\'re not allowed to do this action', Response::HTTP_UNAUTHORIZED);
        }
        try {
            $idea->spams = 0;
            $idea->save();
            return response()->success([
                'Message' => 'The idea marked as Not Spam',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->error('Idea not found', Response::HTTP_NOT_FOUND);
        }
    }
}
