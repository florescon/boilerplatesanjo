<div class="btn-group" role="group" aria-label="Basic example">

  	<x-actions-modal.show-icon target="showModal" emitTo="backend.material.show-material" function="show" :id="$material->id" />

	@if (!$material->trashed())

		<x-actions-modal.edit-icon target="editMaterial" emitTo="backend.material.edit-material" function="edit" :id="$material->id" />
		<x-actions-modal.delete-icon function="delete" :id="$material->id" />

	@else

	    <div class="dropdown">
	      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-ellipsis-v"></i>
	      </a>
	      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
	        <a class="dropdown-item" href="#" wire:click="restore({{ $material->id }})">
	          @lang('Restore')
	        </a>
	      </div>
	    </div>

	@endif
</div>
