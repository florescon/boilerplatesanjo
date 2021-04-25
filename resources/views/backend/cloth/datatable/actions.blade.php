<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.cloth.show-cloth" function="show" :id="$cloth->id" />

	@if (!$cloth->trashed())

	  <x-actions-modal.edit-icon target="editCloth" emitTo="backend.cloth.edit-cloth" function="edit" :id="$cloth->id" />
	  <x-actions-modal.delete-icon function="delete" :id="$cloth->id" />

	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $cloth->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
