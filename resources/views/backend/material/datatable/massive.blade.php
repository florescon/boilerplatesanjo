<label class="form-checkbox text-center">
	<input type="checkbox" wire:model.defer="selectedMassive" value="{{ $model->id }}"
		id="checklist-item-{{ $model->id }}"
	>
	<i class="form-icon"></i>
</label>
