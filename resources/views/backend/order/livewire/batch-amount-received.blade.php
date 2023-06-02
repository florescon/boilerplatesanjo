<div class="border {{ $batch->difference > 0 ? 'border-danger' : ''  }} p-2">

  {{-- @json($difference) --}}

  @if($batch->difference > 0)

    <input type="number" 
      wire:model="received"
      class="form-control mb-2"
      style="color: blue;" 
      {{-- placeholder="{{ $quantity - $received_ }}" --}}
    >

    @if($received)
        <span class='badge badge-primary mt-4' wire:click="receivedAmount({{ $batch_id }})">
          @lang('To receive') {{ $received }}
        </span>
    @endif

  @else
    <span class='badge badge-success'><i class='cil-check'></i></span>
  @endif

</div>
