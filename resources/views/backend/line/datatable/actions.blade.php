<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.line.show-line" function="show" :id="$line->id" />

	@if (!$line->trashed())

	  <x-actions-modal.edit-icon target="editLine" emitTo="backend.line.edit-line" function="edit" :id="$line->id" />
	  <x-actions-modal.delete-icon function="delete" :id="$line->id" />

	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $line->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
