<form wire:submit.prevent="store">
    <x-backend.card>
        <x-slot name="header">
            @lang('Create product')
        </x-slot>

        <x-slot name="headerActions">

            <div wire:loading>
                <em class="text-right text-primary">@lang('Loading')...</em>
            </div>

            <x-utils.link class="card-header-action" :href="route('admin.product.create')" :text="__('Refresh')" />

            <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">

            <div style="width: 500px" id="reader"></div>

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label">@lang('Name')<sup>*</sup></label>

                <div class="col-md-10">
                    <input type="text" name="name" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" maxlength="100" autofocus required />
                                            
                    @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="code" class="col-md-2 col-form-label">@lang('Code')<sup>*</sup></label>

                <div class="col-md-10">
                    <input type="text" name="code" wire:model.lazy="code" class="form-control" placeholder="{{ __('Code') }}" value="{{ old('code') }}" maxlength="100" required />

                    @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label">@lang('Short description')</label>

                <div class="col-md-10">
                    <textarea type="text" name="description" wire:model.lazy="description" class="form-control " placeholder="{{ __('Short description') }}" value="{{ old('description') }}" maxlength="200" ></textarea>

                    @error('description') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->


            <div class="form-group row" wire:ignore>
                <label for="lineselect" class="col-sm-2 col-form-label">@lang('Line')</label>

                <div class="col-sm-10" >
                    <select id="lineselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>

            </div><!--form-group-->


            <div class="form-group row" wire:ignore>
                <label for="brandselect" class="col-sm-2 col-form-label">@lang('Brand')</label>

                <div class="col-sm-10" >
                    <select id="brandselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>

            </div><!--form-group-->


            <div class="form-group row" wire:ignore>
                <label for="colorselect" class="col-sm-2 col-form-label">@lang('Colors')<sup>*</sup></label>

                <div class="col-sm-10" >
                    <select id="colorselect" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10" >
                    @error('colors') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div>

            <div class="form-group row" wire:ignore>
                <label for="sizeselect" class="col-sm-2 col-form-label">@lang('Sizes')<sup>*</sup></label>

                <div class="col-sm-10" >
                    <select id="sizeselect" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true">
                    </select>
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10" >
                    @error('sizes') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div>

            <div class="form-group row">

                <label for="sizeselect" class="col-sm-2 col-form-label">@lang('Automatic codes')</label>

                <div class="col-sm-10" >
                    <label class="c-switch c-switch-primary">
                      <input type="checkbox" class="c-switch-input" wire:model="autoCodes" checked>
                      <span class="c-switch-slider"></span>
                    </label>
                    
                    @if($autoCodes == false)
                        <span class="error" style="color: red;">
                            <p>
                            @lang('Deactivating the automatic code implies that there are codes external to the application and/or it is necessary to do them manually later.')                               
                            </p>
                        </span> 
                    @endif
                </div>

            </div>

            {{-- @json($retail_price) --}}

            <div class="form-group row">
                <label for="price" class="col-md-2 col-form-label">@lang('Price')<sup>*</sup></label>

                <div class="form-row align-items-center ml-2">
                    <div class="{{ $switchIVA ? 'col-md-3' : 'col-md-3' }} mb-3">
                        <input type="number" min="1" step="any" name="price" wire:model="price" class="form-control @error('price') is-invalid  @enderror" placeholder="{{ __('Price') }}" value="{{ old('price') }}" maxlength="100" required/>
                    </div>

                    @if($switchIVA)
                        <div class="col-md-3 mb-3">
                            <input type="text" name="priceIVA" wire:model="priceIVA" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('priceIVA') }}" maxlength="100" required disabled/>
                        </div>
                    @endif

                    <div class="{{ $switchIVA ? 'col-md-6' : 'col-md-8' }} mb-3">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="switchIVA" wire:click="$toggle('switchIVA')">
                          <label class="custom-control-label" for="switchIVA">Precio de proveedor no incluye IVA, incluirlo</label>
                        </div>
                    </div>
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="retail_price" class="col-md-2 col-form-label">@lang('Retail price')</label>

                <div class="col-md-10">
                    <input type="number" min="1" step="any" name="retail_price" wire:model="retail_price" class="form-control" placeholder="{{ __('Retail price') }}" value="{{ old('retail_price') }}" maxlength="100" required />

                    @error('retail_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="average_wholesale_price" class="col-md-2 col-form-label">@lang('Average wholesale price')</label>

                <div class="col-md-10">
                    <input type="number" min="1" step="any" name="average_wholesale_price" wire:model="average_wholesale_price" class="form-control" placeholder="{{ __('Average wholesale price') }}" value="{{ old('average_wholesale_price') }}" maxlength="100" required />

                    @error('average_wholesale_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="average_wholesale_price" class="col-md-2 col-form-label">@lang('Wholesale price')</label>

                <div class="col-md-10">
                    <input type="number" min="1" step="any" name="wholesale_price" wire:model="wholesale_price" class="form-control" placeholder="{{ __('Wholesale price') }}" value="{{ old('wholesale_price') }}" maxlength="100" required />

                    @error('wholesale_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="photo" class="col-sm-2 col-form-label">@lang('Image')</label>

                <div class="col-sm-6" >

                    <div class="custom-file">
                      <input type="file" wire:model="photo" class="custom-file-input @error('photo') is-invalid  @enderror" id="customFileLangHTML">
                      <label class="custom-file-label" for="customFileLangHTML" data-browse="Principal">@lang('Image')</label>
                    </div>

                    <div wire:loading wire:target="photo">@lang('Uploading')...</div>
                    @error('photo') <span class="text-danger">{{ $message }}</span> @enderror

                    @if ($photo)
                        <br><br>
                        @php
                            try {
                               $url = $photo->temporaryUrl();
                               $photoStatus = true;
                            }catch (RuntimeException $exception){
                                $this->photoStatus =  false;
                            }
                        @endphp
                        @if($photoStatus)
                            <img class="img-fluid" alt="Responsive image" src="{{ $url }}">
                        @else
                            @lang('Something went wrong while uploading the file.')
                        @endif
                    @endif

                </div>

                @if($photo)
                    <div wire:loading.remove wire:target="photo"> 
                        <div class="col-sm-3 float-left">
                            <button type="button" wire:click="removePhoto" class="btn btn-light">
                                <i class="cil-x-circle"></i>
                            </button>
                        </div>
                    </div>
                @endif

            </div><!--form-group-->

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create product')</button>
        </x-slot>

    </x-backend.card>
</form>

@push('middle-scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endpush

@push('after-scripts')

    <script>
      $(document).ready(function() {
        $('#lineselect').select2({
          placeholder: '@lang("Choose line")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.line.select') }}',
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

          $('#lineselect').on('change', function (e) {
            var data = $('#lineselect').select2("val");
            @this.set('line', data);
          });


      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
          maximumSelectionLength: 35,
          closeOnSelect: false,
          placeholder: '@lang("Choose colors")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          multiple: true,
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
            var data = $('#colorselect').select2("val");
            @this.set('colors', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#brandselect').select2({
          placeholder: '@lang("Choose brand")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.brand.select') }}',
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

          $('#brandselect').on('change', function (e) {
            var data = $('#brandselect').select2("val");
            @this.set('brand', data);
          });


      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
          maximumSelectionLength: 12,
          closeOnSelect: false,
          placeholder: '@lang("Choose sizes")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          multiple: true,
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
            @this.set('sizes', data);
          });


      });
    </script>

{{--     <script>
        $(document).ready(function () {
            $('.select2').on('change', function (e) {
                let data = $(this).val();
            window.livewire.find('YC3m4IuFJ5rzx6niUzs1').set('product.categories', data);
            });
            Livewire.on('setCategoriesSelect', values => {
                $('.select2').val(values).trigger('change.select2');
            })
        });
    </script>
 --}}

 @endpush