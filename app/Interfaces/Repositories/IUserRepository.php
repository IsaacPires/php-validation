<?php

namespace App\Interfaces\Repositories;

interface IUserRepository
{
    public function paginateUsers($sortBy, $sortDir, $perPage);
    public function createUser(array $data);
    public function findUserById(int $id);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
    public function bulkDeleteUsers(array $ids);
}