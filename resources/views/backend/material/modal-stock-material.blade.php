<x-utils.modal id="updateStockModal" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Update stock feedstock')
  </x-slot>

  <x-slot name="content">
    
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <div class="form-group form-check text-center">
            <label class="c-switch c-switch-primary">
              <input type="checkbox" class="c-switch-input" wire:model="checkboxInput" checked>
              <span class="c-switch-slider"></span>
            </label>
            <div>
              <strong>@lang('Input')</strong>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div class="form-group form-check text-center">
            <label class="c-switch c-switch-danger">
              <input type="checkbox" class="c-switch-input" wire:model="checkboxOutput" checked>
              <span class="c-switch-slider"></span>
            </label>
            <div>
              <strong>@lang('Output')</strong>
            </div>
          </div>
        </div>
      <div>
    </div>

    <table class="table">
      <tbody>

        <tr>
          <th scope="row">@lang('Code')</th>
          <td colspan="2">
            <x-utils.undefined :data="$part_number"/>
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Name')</th>
          <td colspan="2">
            {!! $name !!}            
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Acquisition cost')</th>
          <td colspan="2">
            $<x-utils.undefined :data="$acquisition_cost"/>
          </td>
        </tr>
        
        <tr>
          <th scope="row">@lang('Price')</th>
          <td>
            $<x-utils.undefined :data="$old_price"/>
          </td>
          <td>
            <input type="number" step="any" wire:model.lazy="price" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="@lang('New price') (@lang('Optional'))">
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Stock')<sup>*</sup></th>
          <td>
            <x-utils.undefined :data="$old_stock"/>
            <em class="text-danger">{!! $unit !!}.</em>
          </td>
          <td>
            <input type="number" step="any" wire:model.lazy="stock" class="form-control @error('stock') is-invalid @enderror" id="stock" placeholder="{{ $checkboxOutput ? __('Output') : __('Input') }}">
          </td>
        </tr>

        <tr>
          <th scope="row">@lang('Comment')</th>
          <td colspan="2">
            <input type="text" wire:model.lazy="comment" class="form-control @error('comment') is-invalid @enderror" id="comment" placeholder="@lang('Comment')">
          </td>
        </tr>

      </tbody>
    </table>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>

      @if($checkboxInput == true)
        <button type="submit" class="btn btn-primary">@lang('Save input')</button>
      @endif
      @if($checkboxOutput == true)
        <button type="submit" class="btn btn-danger">@lang('Save output')</button>
      @endif

  </x-slot>
</x-utils.modal>