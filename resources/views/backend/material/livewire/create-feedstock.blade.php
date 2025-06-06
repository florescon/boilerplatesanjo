<form wire:submit.prevent="store">
    <x-backend.card>
        <x-slot name="header">
            @lang('Create feedstock')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="url()->previous()" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <div>
                <div class="form-group row">
                    <label for="part_number" class="col-md-2 col-form-label">@lang('Code')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="part_number" class="form-control" placeholder="{{ __('Code') }}" maxlength="100" />
                        @error('part_number') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}" maxlength="100" />
                        @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="price" class="col-md-2 col-form-label">@lang('Price')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="price" class="form-control" placeholder="{{ __('Price') }}" maxlength="100" />
                        @error('price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                        </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="unitselect" class="col-md-2 col-form-label">@lang('Unit of measurement')</label>

                    <div class="col-md-5" wire:ignore>
                        <select id="unitselect" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>
                    <div class="col-md-5">
                        @error('unit_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="colorselect" class="col-md-2 col-form-label">@lang('Color')</label>

                    <div class="col-md-5" wire:ignore>
                        <select id="colorselect" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>
                    <div class="col-md-5">
                        @error('color_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->


                <div class="form-group row">
                    <label for="sizeselect" class="col-md-2 col-form-label">@lang('Size_')</label>

                    <div class="col-md-5" wire:ignore>
                        <select id="sizeselect" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>

                    <div class="col-md-5">
                        @error('size_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="acquisition_cost" class="col-md-2 col-form-label">@lang('Acquisition cost')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="acquisition_cost" class="form-control" placeholder="{{ __('Acquisition cost') }}" maxlength="100" />
                        @error('acquisition_cost') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="stock" class="col-md-2 col-form-label">@lang('Stock')</label>

                    <div class="col-md-10">
                        <input type="number" step="any" wire:model.lazy="stock" class="form-control" placeholder="{{ __('Stock') }}" maxlength="100" />
                        @error('stock') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="description" class="form-control" placeholder="{{ __('Description') }}" maxlength="100" />
                        @error('description') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

                <div class="form-group row" wire:ignore>
                    <label for="vendorselect" class="col-md-2 col-form-label">@lang('Vendor')</label>

                    <div class="col-md-5">
                        <select id="vendorselect" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row" wire:ignore>
                    <label for="familyselect" class="col-md-2 col-form-label">@lang('Family')</label>

                    <div class="col-md-5">
                        <select id="familyselect" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>
                </div><!--form-group-->

            </div>
            {{-- <livewire:backend.material-table /> --}}
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm float-right text-white" style="background-color: orange;" type="submit">@lang('Save Feedstock')</button>
        </x-slot>

    </x-backend.card>
</form>


@push('middle-scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endpush

@push('after-scripts')

    <script>
      $(document).ready(function() {
        $('#unitselect').select2({
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
            var data = $('#unitselect').select2("val");
            @this.set('unit_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
          placeholder: '@lang("Choose color")',
          width: 'resolve',
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
                                text:  item.name +  (item.color ? ' <div class="box-color justify-content-md-center" style="background-color:' + item.color +'; display: inline-block;"></div> ' : '') + (item.short_name ? item.short_name.sup() : '')
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
            },
            
            escapeMarkup: function(m) { return m; }

          });

          $('#colorselect').on('change', function (e) {
            var data = $('#colorselect').select2("val");
            @this.set('color_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
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
            var data = $('#sizeselect').select2("val");
            @this.set('size_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#vendorselect').select2({
          placeholder: '@lang("Choose vendor")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.vendor.select') }}',
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

          $('#vendorselect').on('change', function (e) {
            var data = $('#vendorselect').select2("val");
            @this.set('vendor_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#familyselect').select2({
          placeholder: '@lang("Choose family")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.family.select') }}',
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

          $('#familyselect').on('change', function (e) {
            var data = $('#familyselect').select2("val");
            @this.set('family_id', data);
          });

      });
    </script>
@endpush