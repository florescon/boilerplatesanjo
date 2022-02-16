<x-utils.modal id="updateStockModal" width="modal-dialog-centered" tform="update">
  <x-slot name="title">
    @lang('Update stock feedstock')
  </x-slot>

  <x-slot name="content">

    <table class="table">
      <tbody>

        <tr>
          <th scope="row">@lang('Part number')</th>
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
          </td>
          <td>
            <input type="number" step="any" wire:model.lazy="stock" class="form-control @error('stock') is-invalid @enderror" id="stock" placeholder="@lang('Add / Subtract') (@lang('Required'))">
          </td>
        </tr>

      </tbody>
    </table>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>