@props(['active' => '', 'text' => '', 'hide' => false, 'icon' => false, 'permission' => false, 'new' => false, 'target' => false])

@if ($permission)
    @if ($logged_in_user->can($permission))
        @if (!$hide)
            <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }} {{ $target ? 'target="_blank"' : '' }}>@if ($icon)<i class="{{ $icon }}"></i> @endif{{ strlen($text) ? $text : $slot }}
                @if($new)
                    <span class="badge bg-info ms-auto">@lang('New')</span>
                @endif
            </a>
        @endif
    @endif
@else
    @if (!$hide)
        <a {{ $attributes->merge(['href' => '#', 'class' => $active]) }} {{ $target ? 'target="_blank"' : '' }} >@if ($icon)<i class="{{ $icon }}"></i> @endif{{ strlen($text) ? $text : $slot }} 
            @if($new)
                <span class="badge bg-info ms-auto">@lang('New')</span>
            @endif
        </a>
    @endif
@endif
