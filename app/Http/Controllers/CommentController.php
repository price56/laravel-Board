<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Board;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as Status;
use Illuminate\Validation\UnauthorizedException;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Board $board, CreateCommentRequest $request): JsonResponse
    {
        $board->comments()->create([
            'contents' => $request->get('contents'),
            'user_id' => \Auth::id(),
        ]);

        return $this->jsonResponse($board->load('comments'), Status::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            throw new UnauthorizedException('user not match');
        }

        $comment = tap($comment)->update([
            'contents' => $request->get('contents'),
        ]);

        return $this->jsonResponse($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            throw new UnauthorizedException('user not match');
        }
        $comment->delete();
        return $this->jsonResponse(null, Status::HTTP_NO_CONTENT);
    }
}
