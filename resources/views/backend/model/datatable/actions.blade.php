<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.model.show-model" function="show" :id="$model->id" />

	@if (!$model->trashed())

	  <x-actions-modal.edit-icon target="editModel" emitTo="backend.model.edit-model" function="edit" :id="$model->id" />
	  <x-actions-modal.delete-icon function="delete" :id="$model->id" />

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
</div>
