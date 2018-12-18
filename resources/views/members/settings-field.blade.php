{{--@php--}}
    {{--$ignore_input = (isset($ignore_input)) ? $ignore_input : false;--}}
{{--@endphp--}}
<div class="col-lg-6 col-md-12">
    <div class="row">
        @if (!isset($label))
            @php
                $label = title_case($name)
            @endphp
        @endif
        <label for="{{ $name }}" class="col-lg-6">{{ $label }}</label>
        <div class="col-lg-6">
            @if (isset($ignore_input) && $ignore_input)
                {{ $output }}
            @elseif (isset($textarea) && $textarea)
                {{ Form::textarea($name, $default, ['rows' => 2, 'cols' => 25]) }}
            @elseif (isset($text_input) && $text_input)
                {{ Form::text($name, $default)}}
            @elseif (isset($email) && $email)
                {{ Form::email($name, $default)}}
            @elseif (isset($tel) && $tel)
                {{ Form::tel($name, $default)}}
            @elseif ($select || is_array($options))
                {{ Form::select($name, $options, $default) }}
            @endif
        </div>
    </div>
</div>