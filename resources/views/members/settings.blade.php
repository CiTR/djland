@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h1>CiTR & Discorder Member Settings</h1>
                </div>

                <div class="card-body">
                    <form action="{{ action('MemberController@update', ['id' => auth()->user()->id]) }}">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="row">
                                    <label for="username" class="col-lg-6">Email</label>
                                    <div class="col-lg-6">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
