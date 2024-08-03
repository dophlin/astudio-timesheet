<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helper\ResponseHelper;
use App\Helper\TokenHelper;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Add filters
        $users = $this->userRepository->index($request);
        return ResponseHelper::success(message: 'List of users.', data: $users, statusCode: 200);
    }

    /**
     * Register a new User
     */
    public function create(RegisterRequest $request)
    {
        try {
            $user = User::Create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if($user) {
                return ResponseHelper::success(message: 'New user has been registered.', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to register the user.', statusCode: 400);
        } catch(Exception $e) {
            \Log::error('Unable to register the user. ' . $e.getMessage());
            return ResponseHelper::error(message: 'Unable to register the user.', statusCode: 500);
        }
    }

    /**
     * Display the specified User.
     */
    public function show(string $id)
    {
        try {
            $data = $this->userRepository->getById($id);
            if(!$data) {
                return ResponseHelper::error(message: 'User not found.', statusCode: 404);
            }
            return ResponseHelper::success(message: 'Specified user.', data: $data, statusCode: 200);
        } catch (Exception $e) {
            return ResponseHelper::error(message: 'Unable to find the user.', statusCode: 500);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        DB::beginTransaction();
        try {
            $affectedRows = $this->userRepository->update($data, $request->id);
            if ($affectedRows > 0) {
                if ($request->has('project_ids')) {
                    $user = User::find($request->id);
                    $user->projects()->sync($request->project_ids);
                }
                DB::commit();
                return ResponseHelper::success(message: 'The user has been updated.', data: null, statusCode: 200);
            } else {
                DB::rollBack();
                return ResponseHelper::error(message: 'The user not found to be updated.', statusCode: 404);
            }
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Unable to update the user. ' . $e->getMessage());
            return ResponseHelper::error(message: 'Unable to update the user.', statusCode: 500);
        }
    }

    /**
     * Remove the specified User.
     */
    public function delete(DeleteUserRequest $request)
    {
        if(TokenHelper::revokeAllTokens($request->id)) {
            $affectedRows = $this->userRepository->delete($request->id);
            if($affectedRows > 0) {
                return ResponseHelper::success(message: 'The user has been deleted.', data: null, statusCode: 200);
            }
            else
                return ResponseHelper::error(message: 'The user not found to be deleted.', statusCode: 404);
        } else {
            return ResponseHelper::error(message: 'Unable to revoke tokens of the user. Delete operation has been failed', statusCode: 500);
        }
    }
}
