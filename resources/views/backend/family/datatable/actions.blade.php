<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.family.show-family" function="show" :id="$family->id" />

	@if (!$family->trashed())

    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.family.modify'))
	    <x-actions-modal.edit-icon target="editFamily" emitTo="backend.family.edit-family" function="edit" :id="$family->id" />
	  @endif
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.family.delete'))
      <x-actions-modal.delete-icon function="delete" :id="$family->id" />
    @endif

	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $family->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif

</div>
