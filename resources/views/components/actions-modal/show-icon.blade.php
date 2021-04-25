@props(['target' => '', 'emitTo' => '', 'function' => 'show', 'id' => null])

<button type="button" data-toggle="modal" data-target="#{{ $target }}" wire:click="$emitTo('{{ $emitTo }}', '{{ $function }}', {{ $id }})" class="btn btn-transparent-dark">
  <i class='far fa-eye'></i>
</button>
