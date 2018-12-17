<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="row">
            @if (!isset($label))
                @php
                    $label = title_case($name)
                @endphp
            @endif
            <label for="{{ $name }}" class="col-lg-6">{{ $label }}</label>
            <div class="col-lg-6">
                @if ($ignore_input)
                    {{ $output }}
                @elseif ($textarea)
                    <textarea name="{{ $name }}" id="{{ $name }}-textarea" cols="30" rows="10">
                        @isset($value){{ $value }}@endisset
                    </textarea>
                @elseif ($select || is_array($options))
                    <select name="{{ $name }}" id="{{ $name }}-dropdown">
                        @foreach ($options as $opt_key => $opt_val)
                            <option value="{{ $opt_key }}" selected="{{ isset($value) && $opt_key == $value }}">
                                {{ $opt_val }}
                            </option>
                        @endforeach
                    </select>
                @elseif
                @endif
            </div>
        </div>
    </div>
</div>