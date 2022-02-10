<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.brand.show-brand" function="show" :id="$brand->id" />

	@if (!$brand->trashed())
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.brand.modify'))
	    <x-actions-modal.edit-icon target="editBrand" emitTo="backend.brand.edit-brand" function="edit" :id="$brand->id" />
	  @endif
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.brand.delete'))
      <x-actions-modal.delete-icon function="delete" :id="$brand->id" />
    @endif
	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $brand->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
