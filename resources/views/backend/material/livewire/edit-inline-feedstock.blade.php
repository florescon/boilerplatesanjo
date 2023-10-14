<div
    x-data="
        {
             isEditing: false,
             isName: '{{ $isName }}',
             focus: function() {
                const textInput = this.$refs.textInput;
                textInput.focus();
                textInput.select();
             }
        }
    "
    x-cloak
>
    <div
        x-show=!isEditing
    >
        <p
            class="card-text"
            x-bind:class="{ 'font-bold': isName }"
            x-on:click="isEditing = true; $nextTick(() => focus())"
        >
            {{ $origName }}
            &nbsp;<i class="cil-pencil"></i>
        </p>
    </div>
    <div x-show=isEditing>
        <form class="flex" wire:submit.prevent="save">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    placeholder="maximo 100 caracteres"
                    x-ref="textInput"
                    wire:model.lazy="newName"
                    x-on:keydown.enter="isEditing = false"
                    x-on:keydown.escape="isEditing = false"
                >
            </div>
        </form>
        <small class="text-xs">@lang('Enter to save, Esc to cancel')</small>
    </div>
</div>
