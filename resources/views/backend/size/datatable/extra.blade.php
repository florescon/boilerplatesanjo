@if($size->is_extra)
	<button wire:loading.attr="disabled" href="#!" wire:click="extra({{ $size->id }})" class="badge badge-primary">@lang('Yes')</button>
@else
	<button wire:loading.attr="disabled" href="#!" wire:click="extra({{ $size->id }})" class="badge badge-danger">@lang('No')</button>
@endif
