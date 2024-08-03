<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function index(Request $request) {
        $firstName = $request->query('first_name');
        $lastName = $request->query('last_name');
        $dateOfBirth = $request->query('date_of_birth');
        $gender = $request->query('gender');
        $email = $request->query('email');

        $query = User::query();

        if ($firstName) {
            $query->where('first_name', 'like', '%' . $firstName . '%');
        }

        if ($lastName) {
            $query->where('last_name', 'like', '%' . $lastName . '%');
        }

        if ($dateOfBirth) {
            $query->whereDate('date_of_birth', $dateOfBirth);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        return $query->get();
    }

    public function getById($id) {
       $user = User::with(['projects', 'timesheets'])->find($id);
       if (!$user) return null;

       $user->projects->each(function ($project) {
           $project->makeHidden('pivot');
       });

       return $user;
    }

    public function store(array $data) {
       return User::create($data);
    }

    public function update(array $data, $id) {
       return User::whereId($id)->update($data);
    }

    public function delete($id) {
       return User::destroy($id);
    }
}
