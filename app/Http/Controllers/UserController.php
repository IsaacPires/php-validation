<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BulkDeleteRequest ;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Interfaces\Service\IUserService;

class UserController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $iUserService) {
        $this->userService = $iUserService;
    }

    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        return $this->userService->index($validated['sort_by'], $validated['sort_dir'], $validated['per_page']);
    }

    public function store(CreateRequest $request)
    {
        $user = $this->userService->store($request->validated());
        return response()->json($user, 201);
    }

    public function show(int $user)
    {
        $foundUser = $this->userService->show($user);
        return response()->json($foundUser);
    }

    public function update(UpdateRequest $request, int $user)
    {   
        $updatedUser = $this->userService->update($user, $request->validated());
        return response()->json($updatedUser);
    }

    public function destroy(int $user)
    {
        $this->userService->destroy($user);
        return response()->json(null, 204);
    }

    public function bulkDelete(BulkDeleteRequest $request)
    {
        $this->userService->bulkDelete($request->validated()['ids']);
        return response()->json(null, 204);
    }
}