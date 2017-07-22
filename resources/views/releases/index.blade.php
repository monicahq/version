@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
              <tbody>
                @foreach($releases as $release)
                <tr>
                  <th scope="row">{{ $release->id }}</th>
                  <td>{{ $release->version }}</td>
                  <td>{{ $release->released_on }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
