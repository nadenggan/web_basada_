<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

class Register extends Controller
{
    public function register(Request $request)
    {
        // Validate
        $request->validate([
            'role' => 'required|in:guru,admin',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // Create New User
        $user = new User();
        $user->name = $request->input('name');
        $user->role =$request->input('role');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->nis = null;
        $user->id_kelas = null;
        $user->alamat = null;

        //dd($user);
        // Save
        $user->save();

        return redirect()->route('login');

    }
}
