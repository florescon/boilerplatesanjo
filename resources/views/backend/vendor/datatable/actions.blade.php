<div class="btn-group" role="group" aria-label="Basic example">

  	{{-- <x-actions-modal.show-icon target="showModal" emitTo="backend.material.show-material" function="show" :id="$material->id" /> --}}

	@if (!$vendor->trashed())

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.vendor.modify'))
			<a type="button" href="{{ route('admin.vendor.edit', $vendor->id) }}" class="btn btn-transparent-dark">
			  <i class='far fa-edit'></i>
			</a>
		@endif

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.vendor.delete'))
			<x-actions-modal.delete-icon function="delete" :id="$vendor->id" />
		@endif

	@else

	    <div class="dropdown">
	      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-ellipsis-v"></i>
	      </a>
	      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
	        <a class="dropdown-item" href="#" wire:click="restore({{ $vendor->id }})">
	          @lang('Restore')
	        </a>
	      </div>
	    </div>

	@endif

</div>
