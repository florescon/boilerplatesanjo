<x-utils.modal id="massiveFeedstocks" tform="store">
  <x-slot name="title">
    @lang('Feedstocks') - @lang('Massive changes')
  </x-slot>

  <x-slot name="content">


    <livewire:backend.material.select-vendor-second />

    <br>

    <livewire:backend.material.select-family-second />

    <br>
    <br>

    @forelse($selectedMassivelabel as $mass)
      {!! $mass !!}<br>
    @empty
      <p class="text-center text-danger pulsingButton">Selecciona algo.</p>
    @endforelse

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($selectedMassive)
        <button type="submit" class="btn btn-primary">@lang('Save')</button>
      @endif
  </x-slot>
</x-utils.modal>