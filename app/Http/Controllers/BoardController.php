<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\CreateBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Models\Board;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response as Status;

class BoardController extends Controller
{
    public function index(): JsonResponse
    {
        $boards = Board::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return response()->json($boards, Status::HTTP_OK, [], JSON_THROW_ON_ERROR);
    }

    public function store(CreateBoardRequest $request): JsonResponse
    {
        $board = Board::create([
            'user_id' => auth()->id(),
            'title' => $request->get('title'),
            'contents' => $request->get('contents'),
        ]);
        return response()->json($board, Status::HTTP_CREATED, [], JSON_THROW_ON_ERROR);
    }

    public function show(Board $board): JsonResponse
    {
        return response()->json($board->load('comments'), Status::HTTP_CREATED, [], JSON_THROW_ON_ERROR);
    }

    public function update(UpdateBoardRequest $request, Board $board): JsonResponse
    {
        if ($board->user_id !== auth()->id()) {
            throw new UnauthorizedException('user not match');
        }
        $board = tap($board)->update($request->only(['title', 'contents']));
        return response()->json($board, Status::HTTP_OK, [], JSON_THROW_ON_ERROR);
    }

    public function destroy(Board $board): JsonResponse
    {
        if ($board->user_id !== auth()->id()) {
            throw new UnauthorizedException('user not match');
        }

        $board->delete();
        return response()->json(null, Status::HTTP_NO_CONTENT, [], JSON_THROW_ON_ERROR);
    }
}
