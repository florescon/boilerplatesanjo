<div
    x-data="
        {
             nameData: false,
             inputText: 'comment',
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
        x-show=!nameData
        class="{{ $origName }}"
    >
        <p  class="card-text {{ $textDanger ? 'text-danger' : '' }}" 
            x-bind:class="{ 'font-weight-bold': inputText }"
            x-on:click="nameData = true; $nextTick(() => focus())"
        >
            {{ $origName ?? '' }}
            {{ $origName ? '' : $text }}
            &nbsp;<i class="cil-pencil"></i>
        </p>
    </div>

    <div x-show=nameData >
        <form class="flex" wire:submit.prevent="save">
            <div class="input-group">
                <input type="text" class="form-control" 
                wire:model.lazy="newName"
                x-ref="textInput"
                maxlength="200"
                x-on:keydown.escape="nameData = false"
                >
              <div class="input-group-append">
                
                {{-- <button class="btn btn-danger btn-sm" x-on:click="nameData = false">
                    <i class="cil-x"></i>
                </button> --}}

                <button class="btn btn-primary btn-sm"  x-on:click="nameData = false" type="submit">
                    ✓
                </button>

              </div>
            </div>
        </form>
        <small class="text-xs">@lang('Enter to save, Esc to cancel')</small>
    </div>
</div>
