<div
    wire:ignore
    x-data
    x-init="
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.setOptions({
            allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            {{-- labelIdle: 'siuui', --}}
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
                },
                revert: (filename, load) => {
                    @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                },
            },
        });
        FilePond.create($refs.input);
    "
>
    <input type="file" x-ref="input">
</div>