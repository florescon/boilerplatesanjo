<x-utils.modal id="reasignDepartament" ariaLabelledby="reasignDepartamentModal" tform="store">
  <x-slot name="title">
    @lang('Reasign departament')
  </x-slot>

  <x-slot name="content">

    <livewire:backend.departament.select-departament-dropdown />

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($departament)
        <button type="submit" class="btn btn-primary">@lang('Save departament')</button>
      @endif
  </x-slot>
</x-utils.modal>