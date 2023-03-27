<div class="btn-group" role="group" aria-label="Basic example">

  	<x-actions-modal.show-icon target="showModal" emitTo="backend.material.show-material" function="show" :id="$material->id" />

	@if (!$material->trashed())

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify'))
			<a type="button" target="_blank" href="{{ route('admin.material.edit', $material->id) }}" class="btn btn-transparent-dark">
			  <i class='far fa-edit'></i>
			</a>
		@endif

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.delete'))
			<x-actions-modal.delete-icon function="delete" :id="$material->id" />
		@endif

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

  	<a href="{{ route('admin.material.t', $material->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i> @lang('Label')</span></a>

</div>
