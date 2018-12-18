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
                    {{--<form action="{{ action('UserController@update', ['id' => $user->id]) }}">--}}
                    {!! Form::open(['action' => ['UserController@update', $user->id], 'method' => 'PUT']) !!}
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="row">
                                    <label for="username" class="col-lg-6">Email</label>
                                    <div class="col-lg-6">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @include('members.settings-field', [
                                'name' => 'first name',
                                'ignore_input' => true,
                                'output' => $user->first_name,
                            ])
                            @include('members.settings-field', [
                                'name' => 'last name',
                                'ignore_input' => true,
                                'output' => $user->last_name,
                            ])
                            @include('members.settings-field', [
                                'name' => 'preferred name',
                                'text_input' => true,
                                'default' => $user->preferred_name,
                            ])
                            @include('members.settings-field', [
                                'name' => 'address',
                                'textarea' => true,
                                'default' => $user->address,
                            ])
                            @include('members.settings-field', [
                                'name' => 'city',
                                'text_input' => true,
                                'default' => $user->city,
                            ])
                            @include('members.settings-field', [
                                'name' => 'province',
                                'select' => true,
                                'options' => ["AB" => "Alberta", "BC" => "British Columbia", "MB" => "Manitoba",
                                              "NB" => "New Brunswick", "NL" => "Newfoundland and Labrador", "NT" => "Northwest Territories",
                                              "NS" => "Nova Scotia", "NU" => "Nunavut", "ON" => "Ontario",
                                              "PE" => "Prince Edward Island", "QC" => "Quebec", "SK" => "Saskatchewan",
                                              "YT" => "Yukon",],
                                'default' => $user->province,
                            ])
                            @include('members.settings-field', [
                                'name' => 'postal code',
                                'text_input' => true,
                                'default' => $user->postal_code,
                            ])
                            @include('members.settings-field', [
                                'name' => 'canadian citizen',
                                'select' => true,
                                'options' => [true => "Yes", false => "No",],
                                'default' => $user->is_canadian_citizen,
                            ])
                            @include('members.settings-field', [
                                'name' => 'email address',
                                'email' => true,
                                'default' => $user->email,
                            ])
                            @include('members.settings-field', [
                                'name' => 'primary number',
                                'tel' => true,
                                'default' => $user->primary_phone,
                            ])
                            @include('members.settings-field', [
                                'name' => 'secondary number',
                                'tel' => true,
                                'default' => $user->secondary_phone,
                            ])

                        </div>
                    <input type="submit">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
