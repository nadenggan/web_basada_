<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // dd($request->all());

        if (Auth::attempt($request->only("email", "password"))) {

            $role = Auth::user()->role;

            if ($role === "admin" || $role === "guru") {
                return redirect("/home");
            } else {

                return redirect("/homeSiswa");
            }
        }

        return redirect("/login");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/login");
    }
}
