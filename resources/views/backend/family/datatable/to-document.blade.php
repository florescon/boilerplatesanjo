@if($family->add_thread)
	<button wire:loading.attr="disabled" href="#!" wire:click="thread({{ $family->id }})" class="badge badge-primary">@lang('Yes')</button>
@else
	<button wire:loading.attr="disabled" href="#!" wire:click="thread({{ $family->id }})" class="badge badge-danger">@lang('No')</button>
@endif
