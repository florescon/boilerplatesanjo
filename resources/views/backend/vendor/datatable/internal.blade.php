@if($vendor->is_internal)
	<button wire:loading.attr="disabled" href="#!" wire:click="internal({{ $vendor->id }})" class="badge badge-primary">@lang('Yes')</button>
@else
	<button wire:loading.attr="disabled" href="#!" wire:click="internal({{ $vendor->id }})" class="badge badge-danger">@lang('No')</button>
@endif
