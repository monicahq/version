<?php

namespace App\Http\Controllers;

use App\Release;
use Validator;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function releaseAdd()
    {
        return view('releases.add');
    }

    public function releases()
    {
        $data = [
          'releases' => Release::orderBy('released_on', 'asc')->orderBy('version', 'desc')->get()
        ];

        return view('releases.index', $data);
    }

    public function releaseStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'version' => 'required',
            'release' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $release = new Release;
        $release->version = $request->get('version');
        $release->notes = $request->get('release');
        $release->released_on = $request->get('date');
        $release->save();

        return redirect('releases');
    }
}
