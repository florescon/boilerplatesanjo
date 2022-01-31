<div class="row input-daterange">
    <div class="col-md-3 mr-2 mb-2 pr-5=">
      <x-input.date wire:model="dateInput" id="dateInput" placeholder="{{ __('From') }}"/>
    </div>

    <div class="col-md-3 mr-2 mb-2">
      <x-input.date wire:model="dateOutput" id="dateOutput" placeholder="{{ __('To') }}"/>
    </div>
    &nbsp;

    <div class="col-md-3 mb-2">
      <div class="btn-group mr-2" role="group" aria-label="First group">
        <button type="button" class="btn btn-outline-primary" wire:click="clearFilterDate" class="btn btn-default">@lang('Clear date')</button>
      </div>
    </div>
    &nbsp;
</div>
