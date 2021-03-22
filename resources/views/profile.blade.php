@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Profile</div>

                <div class="panel-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="mb2 b">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required value="{{ $user->name }}">
                        </div>

                        <div class="form-group">
                            <label for="email" class="mb2 b">Email</label>
                            <input type="text" class="form-control" name="email" id="email" required value="{{ $user->email }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>

                    </form>
                </div>
                <div class="panel-body">
                    <form action="{{ route('password.request') }}" method="POST">
                        @csrf

                        <input type="hidden" id="email" value="{{ $user->email }}">

                        <button type="submit" class="btn btn-primary">Reset your password</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
