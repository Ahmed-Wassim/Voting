<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Resources\StatusResource;

class StatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $statuses = Status::all();
        return response()->success(StatusResource::collection($statuses));
    }
}
