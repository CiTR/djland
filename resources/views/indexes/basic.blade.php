@extends('layouts.app')

@php ($fields = (!empty($fields)) ? $fields : $models->first()->sortable)

@section('content')
    <div class="container">

        <table class="table">
            <thead>
            <tr>
                @foreach ($fields as $field)
                    <th scope="col">@sortablelink($field, title_case($field))</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($models as $model)
                <tr>
                    @foreach ($fields as $field)
                        <td>{{$model->$field}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>

        </table>
        {!! $models->appends(request()->except('page'))->render() !!}
    </div>
@endsection

