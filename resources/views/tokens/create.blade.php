@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new token</div>

                <div class="panel-body">
                    <form action="{{ route('tokens.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="token_name" class="mb2 b">Token name</label>
                            <input type="text" class="form-control" name="token_name" id="token_name" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Validate</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
