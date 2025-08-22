<?php

namespace App\Interfaces\Service;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserService
{
    public function index($sortBy, $sortDir, $perPage): LengthAwarePaginator;
    public function store(array $data): User;
    public function show(int $id): User;
    public function update(int $id, array $data): User;
    public function destroy(int $id): bool;
    public function bulkDelete(array $ids): int;
}