<div class="custom-control custom-switch custom-control-inline fw-bolder font-weight-bold text-primary border-0 pt-3 pr-3">

    @lang('Family')

    <div class="col-md-9" wire:ignore>
        <select id="familyselect" name="family_id" id="family_id" class="custom-select" style="width: 100%;" aria-hidden="true">
        </select>
    </div>

    @if($family_id)
        <button type="button" class="btn btn-outline-primary" wire:click="clear" class="btn btn-default">@lang('Clear')</button>
    @endif

</div>
