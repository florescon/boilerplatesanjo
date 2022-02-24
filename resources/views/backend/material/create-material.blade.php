<x-utils.modal id="createMaterial" ariaLabelledby="createMaterialModal" tform="store">
  <x-slot name="title">
    @lang('Create feedstock')
  </x-slot>

  <x-slot name="content">

    <div class="form-group row">
      <label for="part_number" class="col-sm-2 col-form-label">@lang('Part number')</label>
      <div class="col-sm-10">
        <input type="text" wire:model.defer="part_number" class="form-control @error('part_number') is-invalid @enderror" id="part_number">
        @error('part_number') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label">@lang('Name')<sup>*</sup></label>
      <div class="col-sm-10">
        <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror" id="name">
        @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="price" class="col-sm-2 col-form-label">@lang('Price')<sup>*</sup></label>
      <div class="col-sm-10">
        <input type="number" placeholder="{{ __('Scale of 2 decimal digits') }}" wire:model.defer="price" step="any" class="form-control @error('price') is-invalid @enderror">
        @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="acquisition_cost" class="col-sm-2 col-form-label">@lang('Acquisition cost')</label>
      <div class="col-sm-10">
        <input type="number" placeholder="{{ __('Scale of 2 decimal digits') }}" wire:model.defer="acquisition_cost" step="any" class="form-control @error('acquisition_cost') is-invalid @enderror">
        @error('acquisition_cost') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row" wire:ignore id="data-importador">
      <label for="unitselect" class="col-sm-2 col-form-label">@lang('Unit')<sup>*</sup></label>
      <div class="col-sm-10" >
        <select id="unitselect" class="custom-select" style="width: 100%;" aria-hidden="true" required>
        </select>
      </div>
    </div>

    <div class="form-group row" wire:ignore id="data-importador">
      <label for="colorselect" class="col-sm-2 col-form-label">@lang('Color')<sup>*</sup></label>
      <div class="col-sm-10" >
        <select id="colorselect" class="custom-select" style="width: 100%;" aria-hidden="true" required>
        </select>
      </div>
    </div>

    <div class="form-group row" wire:ignore id="data-importador">
      <label for="sizeselect" class="col-sm-2 col-form-label">@lang('Size_')</label>
      <div class="col-sm-10" >
        <select id="sizeselect" class="custom-select" style="width: 100%;" aria-hidden="true">
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="stock" class="col-sm-2 col-form-label">@lang('Stock')<sup>*</sup></label>
      <div class="col-sm-10">
        <input type="number" placeholder="{{ __('Maximum scale of 8 decimal digits') }}" wire:model.defer="stock" step="any" class="form-control @error('stock') is-invalid @enderror">
        @error('stock') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label">@lang('Description')</label>
      <div class="col-sm-10">
        <input type="text" wire:model.defer="description" class="form-control @error('description') is-invalid @enderror" id="description">
        @error('description') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
      </div>
    </div>

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

@push('after-scripts')

    <script>
      $(document).ready(function() {
        $('#unitselect').select2({
          dropdownParent: $("#createMaterial"),
          placeholder: '@lang("Choose unit")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.unit.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });

          $('#unitselect').on('change', function (e) {
              @this.set('unit_id', e.target.value);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
          dropdownParent: $("#createMaterial"),
          placeholder: '@lang("Choose color")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.color.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });

          $('#colorselect').on('change', function (e) {
              @this.set('color_id', e.target.value);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
          dropdownParent: $("#createMaterial"),
          placeholder: '@lang("Choose size")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.size.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });

          $('#sizeselect').on('change', function (e) {
              @this.set('size_id', e.target.value);
          });

      });
    </script>

  <script type="text/javascript">
    Livewire.on("materialResetSelect", () => {
      $('#unitselect').val([]).trigger("change");
      $('#colorselect').val([]).trigger("change");
      $('#sizeselect').val([]).trigger("change");
    });
  </script>
@endpush
