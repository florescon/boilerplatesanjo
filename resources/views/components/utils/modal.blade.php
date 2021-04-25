@props(['id' => null, 'width' => null, 'tform' => null, 'footer' => null, 'ariaLabelledby' => 'exampleModalLabel'])
<div 
    wire:ignore.self  
    class="modal fade"  
    id="{{ $id }}" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="{{ $ariaLabelledby }}" 
    aria-hidden="true"
    
>
        <div class="modal-dialog {{ $width }}" role="document">
            <div class="modal-content">
                
                <div class="modal-header">
                <h5 class="modal-title" id="{{ $ariaLabelledby }}">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if($tform)
                    <form wire:submit.prevent="{{ $tform }}">
                @endif
                        <div class="modal-body">
                            {{ $content }}
                        </div>

                        <div class="modal-footer">
                            {{ $footer }}
                        </div>
                @if($tform)
                    </form>
                @endif

            </div>
        </div>
</div>