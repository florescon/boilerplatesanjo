@props([
    'nameData' => '',
    'inputText' => '', 
    'originalInput' => '',
    'wireSubmit' => '#',
    'modelName' => '#',
    'inputType' => 'text',
    "maxlength" => '100',
    "className" => 'mt-4',
    'beforeName' => '',
    'extraName' => '',
])

<div
    x-data="
        {
             nameData: false,
             inputText: '{{ $inputText }}',
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
        class="{{ $className }}"
    >
        <p class="card-text" 
            x-bind:class="{ 'font-weight-bold': inputText }"
            x-on:click="nameData = true; $nextTick(() => focus())"
        >{{ $beforeName }} {{ $originalInput }}
            &nbsp;<i class="cil-pencil"></i>
        <em>
            {{ $extraName }}
        </em>
        </p>
    </div>

    <div x-show=nameData >
        <form class="flex" wire:submit.prevent="{{ $wireSubmit }}">

            <div class="input-group">
                <input type="{{ $inputType }}" maxlength="{{ $maxlength }}" class="form-control" 
                wire:model.lazy="{{ $modelName }}"
                x-ref="textInput"
                x-on:keydown.escape="nameData = false"
                >
              <div class="input-group-append">
                <span class="input-group-text" x-on:click="nameData = false">
                    <i class="cil-x"></i>
                </span>

                <button class="btn btn-primary"  x-on:click="nameData = false" type="submit"><i class="cil-check-alt"></i></button>

              </div>
            </div>
        </form>
        <small class="text-xs">@lang('Enter to save, Esc to cancel')</small>
    </div>
</div>
