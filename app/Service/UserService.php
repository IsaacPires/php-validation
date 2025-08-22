<?php

namespace App\Service;

use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Service\IUserService;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements IUserService
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($sortBy, $sortDir, $perPage): LengthAwarePaginator
    {
        return $this->userRepository->paginateUsers($sortBy, $sortDir, $perPage);
    }
    
    public function store(array $data): User
    {
        return $this->userRepository->createUser($data);
    }

    public function show(int $id): User
    {
        return $this->userRepository->findUserById($id);
    }

    public function update(int $id, array $data): User
    {
        return $this->userRepository->updateUser($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->userRepository->deleteUser($id);
    }

    public function bulkDelete(array $ids): int
    {
        return $this->userRepository->bulkDeleteUsers($ids);
    }
}