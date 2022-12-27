@props(['target' => '', 'icon' => '', 'emitTo' => '', 'function' => 'show', 'id' => null])

<button type="button" data-toggle="modal" data-target="#{{ $target }}" wire:click="$emitTo('{{ $emitTo }}', '{{ $function }}', {{ $id }})" class="btn btn-transparent-dark">
  <i class='{{ $icon }}'></i>
</button>
