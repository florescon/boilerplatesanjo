<div class="btn-group" role="group" aria-label="Basic example">

  <x-actions-modal.show-icon target="showModal" emitTo="backend.thread.show-thread" function="show" :id="$thread->id" />

	@if (!$thread->trashed())
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.thread.modify'))
  	  <x-actions-modal.edit-icon target="editThread" emitTo="backend.thread.edit-thread" function="edit" :id="$thread->id" />
    @endif
    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.thread.delete'))
      <x-actions-modal.delete-icon function="delete" :id="$thread->id" />
    @endif
	@else

    <div class="dropdown">
      <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="#" wire:click="restore({{ $thread->id }})">
          @lang('Restore')
        </a>
      </div>
    </div>

	@endif
</div>
