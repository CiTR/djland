@extends('layouts.app')

@section('content')
    <div class="container">

        <table class="table">
            <thead>
            <tr>
                <th scope="col">@sortablelink('id', 'Id')</th>
                <th scope="col">@sortablelink('first_name', 'First Name')</th>
                <th scope="col">@sortablelink('email', 'Email')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->email}}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
        {!! $users->appends(\Request::except('page'))->render() !!}
    </div>
@endsection

