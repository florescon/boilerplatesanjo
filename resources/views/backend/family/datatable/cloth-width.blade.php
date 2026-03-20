@if($family->cloth_width)
	<button wire:loading.attr="disabled" href="#!" wire:click="cloth({{ $family->id }})" class="badge badge-primary">@lang('Yes')</button>
@else
	<button wire:loading.attr="disabled" href="#!" wire:click="cloth({{ $family->id }})" class="badge badge-danger">@lang('No')</button>
@endif
