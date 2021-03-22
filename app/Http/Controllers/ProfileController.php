<?php

namespace App\Http\Controllers;

use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('profile')
            ->withUser(Auth::user());
    }

    public function update(Request $request)
    {
        User::update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return $this->index();
    }
}
