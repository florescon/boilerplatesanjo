<label class="form-checkbox text-center">
	<input type="checkbox" wire:model.defer="selectedMassive" wire:key="massive-{{ $model->id }}" value="{{ $model->id }}"
		id="massive-{{ $model->id }}"
	>
	<i class="form-icon"></i>
</label>
