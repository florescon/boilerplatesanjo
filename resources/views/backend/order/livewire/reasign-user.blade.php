<x-utils.modal id="reasignUser" ariaLabelledby="reasignUserModal" tform="store">
  <x-slot name="title">
    @lang('Reasign user')
  </x-slot>

  <x-slot name="content">

    <livewire:backend.user.only-users-dropdown />

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      @if($user)
        <button type="submit" class="btn btn-primary">@lang('Save user')</button>
      @endif
  </x-slot>
</x-utils.modal>