@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New token</div>

                <div class="panel-body">
                    <span>This is your new token, save it!</span>
                    <input readonly type="text" value="{{ $token }}" />
                </div>

                <div class="panel-body">
                    <a href="{{ route('tokens.index') }}">Tokens list</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
