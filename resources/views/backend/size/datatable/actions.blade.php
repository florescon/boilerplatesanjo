<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.size.show-size" function="show" :id="$size->id" />

	@if (!$size->trashed())
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.size.modify'))
  	  <x-actions-modal.edit-icon target="editSize" emitTo="backend.size.edit-size" function="edit" :id="$size->id" />
    @endif
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.size.delete'))
      <x-actions-modal.delete-icon function="delete" :id="$size->id" />
    @endif
	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $size->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
