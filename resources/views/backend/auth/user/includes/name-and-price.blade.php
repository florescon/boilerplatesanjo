{{ $user->name }}
<div class="small text-muted">
	{{ $user->customer->type_price_label ?? __('Retail price') }}
</div>