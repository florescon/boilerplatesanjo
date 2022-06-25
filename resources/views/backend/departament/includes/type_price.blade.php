@if ($departament->isRetail())
    @lang('Retail price')
@elseif ($departament->isAverageWholesale())
    @lang('Average wholesale price')
@elseif ($departament->isWholesale())
    @lang('Wholesale price')
@elseif ($departament->isSpecial())
    @lang('Special price')
@else
    @lang('N/A')
@endif
