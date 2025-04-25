<!-- Modal -->
<div wire:ignore.self class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('Update prices')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body">

          <div>
              <h2>@lang('Select sizes'): </h2>

            <div class="row">
              <div class="col">

                <x-utils.virtual-select 
                  wire:model.defer="selected_sizes"
                  :options="[
                      'options' => collect($unique_sizes)->map(function($size) {
                          return [
                              'label' => $size->name,
                              'value' => $size->id
                          ];
                      })->toArray(),
                     'selectedValue' => [],
                     'multiple' => true,
                     'showValueAsTags' => true,
                  ]"
                />

              </div>
              <div class="col">
                <div>
                  <input type="number" class="form-control" wire:model.defer="{{ $getField }}" id="{{ $getField }}" min="0" placeholder="{{ __('Price') }}">
                </div>
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
          <button wire:click="theSpecialPrice" class="btn btn-primary">@lang('Save')</button>
        </div>
    </div>
  </div>
</div>
