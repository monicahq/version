@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Tokens</div>

                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <div class="panel-body">
                    <ul>
                    @foreach ($tokens as $token)
                    <li>
                        <span>
                            {{ $token->name }}
                        </span>
                        <span>
                            <form action="{{ route('tokens.destroy', $token) }}" id="tokens-destroy-form" method="POST">
                                @csrf
                                @method('DELETE')

                                <a href="#"
                                    onclick="event.preventDefault();
                                        document.getElementById('tokens-destroy-form').submit();">
                                    Delete
                                </a>
                            </form>
                        </span>
                    </li>
                    @endforeach
                    </ul>
                </div>
                <div class="panel-body">
                    <a class="btn" href="{{ route('tokens.create') }}">Create a new token</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
