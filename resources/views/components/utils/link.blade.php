@props(['active' => '', 'text' => '', 'hide' => false, 'icon' => false, 'permission' => false, 'new' => false, 'old' => false, 'target' => false])

@if ($permission)
    @if ($logged_in_user->can($permission))
        @if (!$hide)
            <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }} {{ $target ? 'target="_blank"' : '' }}>@if ($icon)<i class="{{ $icon }}"></i> @endif{{ strlen($text) ? $text : $slot }}
                @if($new)
                    <span class="badge bg-primary ms-auto">@lang('New')</span>
                @endif

                @if($old)
                    <span class="badge bg-danger ms-auto">@lang('Old')</span>
                @endif
            </a>
        @endif
    @endif
@else
    @if (!$hide)
        <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }} {{ $target ? 'target="_blank"' : '' }} >@if ($icon)<i class="{{ $icon }}"></i> @endif{{ strlen($text) ? $text : $slot }} 
            @if($new)
                <span class="badge bg-primary ms-auto">@lang('New')</span>
            @endif

            @if($old)
                <span class="badge bg-danger ms-auto">@lang('Old')</span>
            @endif
        </a>
    @endif
@endif
