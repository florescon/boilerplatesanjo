@props([
    'borderClass' => 'primary',
])

<div
	    x-data="{ value: @entangle($attributes->wire('model')), picker: undefined }"
        x-init="new Pikaday({ field: $refs.input, format: 'YYYY-MM-DD', onSelect: function() {
            console.log(this.getMoment().format('DD-MM-YYYY'));
        } })"
	    x-on:change="value = $event.target.value"

>

	<input 
        {{ $attributes->whereDoesntStartWith('wire:model') }}
        x-ref="input"
        x-bind:value="value"
		class="form-control border-{{ $borderClass }}" 
		autocomplete="off"
		readonly
		>
</div>