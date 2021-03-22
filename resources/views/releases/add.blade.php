@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <form class="form-horizontal" method="post" action="/releases/add">
                @csrf
                <fieldset>
                    <legend>Add a new release</legend>

                    <div class="form-group">
                      <label for="version" class="control-label col-sm-2">Version</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="version" id="version">
                        <p class="help-block">Last used version: </p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="release">Release description</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="release" name="release" rows="7"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="date">Date of the release</label>
                      <div class="col-sm-10">
                        <input type="date" class="form-control" name="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                      </div>
                    </div>

                    <div class="form-group">
                    <label class="control-label col-sm-2"></label>
                      <div class="text-right col-sm-10">
                        <button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary" aria-label="Single Button">Save</button>
                      </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
@endsection
