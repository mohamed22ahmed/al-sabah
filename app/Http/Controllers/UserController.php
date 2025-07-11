<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('admin/users/index', [
            'users' => UserResource::collection($users),
        ]);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json(UserResource::collection($users));
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json(new UserResource($user));
    }

    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'تم حفظ المستخدم بنجاح'
        ]);
    }

    public function update($id, UserRequest $request)
    {
        $user = User::find($id);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'تم تعديل المستخدم بنجاح'
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }
}
