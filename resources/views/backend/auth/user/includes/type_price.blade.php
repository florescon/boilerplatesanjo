@if (optional($user->customer)->isRetail())
    @lang('Retail price')
@elseif (optional($user->customer)->isAverageWholesale())
    @lang('Average wholesale price')
@elseif (optional($user->customer)->isWholesale())
    @lang('Wholesale price')
@elseif (optional($user->customer)->isSpecial())
    @lang('Special price')
@else
    @lang('N/A')
@endif
