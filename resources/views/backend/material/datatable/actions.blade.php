<div class="btn-group" role="group" aria-label="Basic example">

  	<x-actions-modal.show-icon target="showModal" emitTo="backend.material.show-material" function="show" :id="$model->id" />

	@if (!$model->trashed())

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify'))
			@if($model->id)
				<a type="button" target="_blank" href="{{ route('admin.material.edit', $model->id) }}" class="btn btn-transparent-dark">
				  <i class='far fa-edit'></i>
				</a>
			@endif
		@endif

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.delete'))
			<x-actions-modal.delete-icon function="delete" :id="$model->id" />
		@endif

	@else

	    <div class="dropdown">
	      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="fas fa-ellipsis-v"></i>
	      </a>
	      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
	        <a class="dropdown-item" href="#" wire:click="restore({{ $model->id }})">
	          @lang('Restore')
	        </a>
	      </div>
	    </div>

	@endif

	@if($model->id)
		<a href="{{ route('admin.material.t', $model->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i> @lang('Label')</span></a>
	@endif

</div>
