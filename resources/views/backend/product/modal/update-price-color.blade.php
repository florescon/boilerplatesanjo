<!-- Modal -->
<div wire:ignore.self class="modal fade"  id="exampleModalColor" tabindex="-1" role="dialog" aria-labelledby="exampleModalColorLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalColorLabel">@lang('Update prices')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body">

          <div>
              <h2>@lang('Select colors'): </h2>

            <div class="row">
              <div class="col">
                <div>
                  <input type="number" class="form-control" wire:model.defer="{{ $getField }}" id="{{ $getField }}" min="0" placeholder="{{ __('Price') }}">
                </div>
              </div>
              <div class="col">

                <x-utils.virtual-select 
                  wire:model.defer="selected_colors"
                  :options="[
                      'options' => collect($unique_colors)->map(function($color) {
                          return [
                              'label' => $color->name,
                              'value' => $color->id
                          ];
                      })->toArray(),
                     'selectedValue' => [],
                     'multiple' => true,
                     'showValueAsTags' => true,
                  ]"
                />

              </div>
            </div>

            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
          <button wire:click="theSpecialPriceColor" class="btn btn-primary">@lang('Save')</button>
        </div>
    </div>
  </div>
</div>
