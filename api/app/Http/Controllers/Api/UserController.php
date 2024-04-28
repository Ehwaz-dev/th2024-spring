<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'articles' => fn($q) => $q->take(20)
                ->with(['comments' => fn($q) => $q->take(3)])
                ->withCount(['likes', 'comments']),
            'activeEvents' => fn($q) => $q->take(3)
                ->with(['tags'])
                ->withCount(['likes', 'comments']),
            'passedEvents' => fn($q) => $q->orderBy('end', 'desc')
                ->take(3)
                ->with('tags')
                ->withCount(['likes', 'comments']),
        ]);
        return UserResource::make($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->load([
            'articles' => fn($q) => $q->take(20)
                ->with('comments')
                ->withCount(['likes', 'comments']),
            'activeEvents' => fn($q) => $q->take(3)
                ->with(['tags'])
                ->withCount(['likes', 'comments']),
            'passedEvents' => fn($q) => $q->orderBy('end', 'desc')
                ->take(3)
                ->with('tags')
                ->withCount(['likes', 'comments']),
        ]);

        return UserResource::make($user);
    }
}
