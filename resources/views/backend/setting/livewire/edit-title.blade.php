<div class="d-inline">
	<x-input.input-alpine nameData="isEditing" :inputText="$isTitle" :originalInput="$origTitle" wireSubmit="save" modelName="title" :extraName="$extraName" />
	@error('title') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
</div>