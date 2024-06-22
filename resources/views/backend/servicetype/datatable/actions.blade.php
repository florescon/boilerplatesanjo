<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.service-type.show-service" function="show" :id="$servicetype->id" />

	@if (!$servicetype->trashed())
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.servicetype.modify'))
  	  <x-actions-modal.edit-icon target="editServiceType" emitTo="backend.service-type.edit-service" function="edit" :id="$servicetype->id" />
    @endif
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.servicetype.delete'))
      <x-actions-modal.delete-icon function="delete" :id="$servicetype->id" />
    @endif
	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $servicetype->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
