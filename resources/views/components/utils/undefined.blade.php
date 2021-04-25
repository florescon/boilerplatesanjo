@props(['data'])

@if($data)
    {{ $data }}
@else
    <span class='badge badge-secondary'>@lang('undefined')</span>
@endif