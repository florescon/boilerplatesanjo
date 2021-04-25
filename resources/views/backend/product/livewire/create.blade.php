<form wire:submit.prevent="store">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create product')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">

                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" name="name" wire:model="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') }}" maxlength="100" required />
                        
                        @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="code" class="col-md-2 col-form-label">@lang('Code')</label>

                    <div class="col-md-10">
                        <input type="text" name="code" wire:model="code" class="form-control" placeholder="{{ __('Code') }}" value="{{ old('code') }}" maxlength="100" required />

                        @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>

                    <div class="col-md-10">
                        <textarea type="text" name="description" wire:model="description" class="form-control " placeholder="{{ __('Description') }}" value="{{ old('description') }}" maxlength="200" ></textarea>

                        @error('description') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

                <div class="form-group row" wire:ignore>
                    <label for="colorselect" class="col-sm-2 col-form-label">@lang('Colors')</label>

                    <div class="col-sm-10" >
                        <select id="colorselect" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true" >
                        </select>
                    </div>


                </div><!--form-group-->


                <div class="form-group row" wire:ignore>
                    <label for="sizeselect" class="col-sm-2 col-form-label">@lang('Sizes')</label>

                    <div class="col-sm-10" >
                        <select id="sizeselect" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true">
                        </select>
                    </div>

                </div><!--form-group-->


                <div class="form-group row">
                    <label for="photo" class="col-sm-2 col-form-label">@lang('Image')</label>

                    <div class="col-sm-6" >

                        <div class="custom-file">
                          <input type="file" wire:model="photo" class="custom-file-input" id="customFileLangHTML">
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

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
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
            @this.set('color_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
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
            @this.set('size_id', data);
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