<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('admin/users/index', [
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function store(Request $request){
        User::create($request->all());
        return response()->json([
            'message' => 'تم حفظ الادمن بنجاح'
        ]);
    }

    public function update($id, Request $request)
    {
        User::find($id)->update($request->all());
        return response()->json([
            'message' => 'تم تعديل الادمن بنجاح'
        ]);
    }

    public function delete($id)
    {
        User::find($id)->delete();
        return response()->json([
            'message' => 'تم حذف الادمن بنجاح'
        ]);
    }
}
