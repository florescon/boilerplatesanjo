@if ($user->isAdmin())
    @lang('Administrator')
@elseif ($user->isUser())
    @lang('Customer')
@else
    @lang('N/A')
@endif
