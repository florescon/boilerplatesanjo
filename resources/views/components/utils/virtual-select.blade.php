@props([
	'id' => 'virtual-select' .  \Illuminate\Support\Str::random(10),
	'options' => []
])

@php
	$virtualSelectOptions = array_merge([
		'ele' => '#'.$id,
		'search' => true,
		'options' => [],
		// 'disableSelectAll' => true,
		'placeholder' => __('Select'),
		'searchPlaceholderText' => __('Search'),
		'noSearchResultsText' => __('No search results'),
		// 'showSelectedOptionsFirst' => true,
		// 'showValueAsTags' => true,
	], $options);
@endphp
 
<div wire:ignore wire:key="{{ md5(collect($options)) }}">
	<div
		x-data="{
			select: @entangle($attributes->wire('model')),
			initVirtualSelect() {
				VirtualSelect.init({{ collect($virtualSelectOptions) }})
			}
		}"
		id="{{ $id }}"
		x-ref="virtualSelect"
		x-init="initVirtualSelect()"
		x-cloak
		x-on:change="select = event.target.value"

		
	></div>
</div>
 
@once
	@push('after-styles')
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.21/dist/virtual-select.min.css">
	@endpush
	@push('after-scripts')
		<script src="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.21/dist/virtual-select.min.js"></script>
	@endpush	
@endonce