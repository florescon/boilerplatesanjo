@if (optional($user->customer)->isRetail())
    @lang('Retail price')
@elseif (optional($user->customer)->isAverageWholesale())
    @lang('Average wholesale price')
@elseif (optional($user->customer)->isWholesale())
    @lang('Wholesale price')
@else
    @lang('N/A')
@endif
