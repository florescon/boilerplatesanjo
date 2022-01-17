@if ($departament->isRetail())
    @lang('Retail price')
@elseif ($departament->isAverageWholesale())
    @lang('Average wholesale price')
@elseif ($departament->isWholesale())
    @lang('Wholesale price')
@else
    @lang('N/A')
@endif
