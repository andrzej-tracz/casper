<?php

namespace App\Http\Controllers\API;

use App\Casper\Repository\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(UserRepository $repository, Request $request)
    {
        $search = $request->input('search');
        $users = $repository->searchUsers($search);

        return User::collection($users);
    }
}
