<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function paginateUsers($sortBy, $sortDir, $perPage)
    {
        $sortableColumns = array_merge((new User)->getFillable(), ['id']);

        $query = User::query();

        if (in_array($sortBy, $sortableColumns))
            $query->orderBy($sortBy, $sortDir);
        else 
            $query->orderBy('id', 'desc');
        
        return $query->paginate($perPage);
    }

    public function createUser(array $data)
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function findUserById(int $id)
    {
        return User::findOrFail($id);
    }

    public function updateUser(int $id, array $data)
    {
        $user = User::findOrFail($id);

        if (isset($data['password'])) 
            $data['password'] = Hash::make($data['password']);
        
        
        $user->update($data);

        return $user;
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function bulkDeleteUsers(array $ids)
    {
        return User::whereIn('id', $ids)->delete();
    }
}