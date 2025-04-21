<div class="btn-group" role="group" aria-label="Basic example">


	<a type="button" data-toggle="modal" data-target="#showModal" wire:click="$emitTo('backend.material.show-material', 'show', {{ $model->id }})" class="mr-2">
	  <i class='far fa-eye'></i>
	</a>

	@if (!$model->trashed())

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify'))
			@if($model->id)
				<a type="button" target="_blank" href="{{ route('admin.material.kardex', $model->id) }}" class="mr-2">
					Kardex
				</a>

				<a type="button" target="_blank" href="{{ route('admin.material.edit', $model->id) }}" class="mr-2">
				  <i class='far fa-edit'></i>
				</a>
			@endif
		@endif

		@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.delete'))

			<div class="dropdown mr-2">
			  <a class=" btn-icon-only " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <i class="cil-options"></i>
			  </a>
			  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
			    <a class="dropdown-item" wire:click="delete({{ $model->id }})">@lang('Delete') </a>
			  </div>
			</div>

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
		<a type="button" class="" href="{{ route('admin.material.t', $model->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i></span></a>
 	@endif

</div>
