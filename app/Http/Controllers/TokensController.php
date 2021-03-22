<?php

namespace App\Http\Controllers;

use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class TokensController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tokens = $request->user()->tokens()->get();

        return view('tokens.index')
            ->withTokens($tokens);
    }

    public function create(Request $request)
    {
        return view('tokens.create')
            ->withUser($request->user());
    }

    public function store(Request $request)
    {
        $token = $request->user()->createToken($request->token_name);

        return view('tokens.store')
            ->withToken($token->plainTextToken);
    }

    public function destroy(Request $request, PersonalAccessToken $token)
    {
        $tokenable = $token->tokenable()->get()->first();

        if ($tokenable->id !== $request->user()->id) {
            abort(401);
        }

        $request->user()->tokens()->find($token->id)->delete();

        return redirect()->route('tokens.index')
            ->with('status', 'Token deleted');
    }
}
