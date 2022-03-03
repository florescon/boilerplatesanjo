<div class="border border-danger p-2">
  
    @if($assignment->received < $assignment->quantity && !$assignment->isOutput())
        <input type="number" 
          wire:model="received"
          class="form-control mb-2"
          style="color: blue;" 
          {{-- placeholder="{{ $quantity - $received_ }}" --}}
        >

        @error('received') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

        @if($assignment->isOutput())
          <span class='badge badge-success'><i class='cil-check'></i></span>
        @else
            @if(!$received)
              <span class='badge badge-danger mt-4' wire:click="outputUpdate({{ $assignment_id }})">
                @lang('To receive') {{ $assignment->available }}
              </span>
            @endif
        @endif

    @else
        <em>@lang('Total received')</em> 
    @endif

    {{-- {{ $assignment_id }} --}}

    @if($received && !$assignment->isOutput())
        <span class='badge badge-primary mt-4' wire:click="receivedAmount({{ $assignment_id }})">
          @lang('To receive') {{ $received }}
        </span>
    @endif
</div>
