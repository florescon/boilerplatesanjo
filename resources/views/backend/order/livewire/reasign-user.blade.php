<x-utils.modal id="reasignUser" ariaLabelledby="reasignUserModal" tform="store">
  <x-slot name="title">
    @lang('Reasign customer')
  </x-slot>

  <x-slot name="content">

    <div class="alert alert-danger" role="alert">
      Solamente es válido 1 vez por pedido.
    </div>

    <livewire:backend.user.only-users-dropdown />

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($user)
        <button type="submit" class="btn btn-primary">@lang('Save')</button>
      @endif
  </x-slot>
</x-utils.modal>