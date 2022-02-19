<div class="d-inline">
	<x-input.input-alpine nameData="isEditing" :inputText="$isSort" :originalInput="$origSort" wireSubmit="save" modelName="sort" :extraName="$extraName" />
	@error('sort') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>