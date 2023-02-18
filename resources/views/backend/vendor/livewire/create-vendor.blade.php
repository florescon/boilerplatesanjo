<form wire:submit.prevent="store">
    <x-backend.card>
        <x-slot name="header">
            @lang('Create vendor')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.vendor.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <div>
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="name" class="form-control" placeholder="{{ __('Name') }}" maxlength="100" required/>
                        @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Short name')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="short_name" class="form-control" placeholder="{{ __('Short name') }}" maxlength="15" required/>
                        @error('short_name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label">@lang('Email')</label>

                    <div class="col-md-10">
                        <input type="email" wire:model.lazy="email" class="form-control" placeholder="{{ __('Email') }}" maxlength="100" />
                        @error('email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="phone" class="col-md-2 col-form-label">@lang('Phone')</label>

                    <div class="col-md-10">
                        <input type="number" wire:model.lazy="phone" class="form-control" placeholder="{{ __('Phone') }}" maxlength="15" />
                        @error('phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                        </div>
                </div><!--form-group-->

                <div class="form-group row" wire:ignore>
                    <label for="cityselect" class="col-md-2 col-form-label">@lang('City')</label>

                    <div class="col-md-10">
                        <select id="cityselect" class="custom-select" style="width: 100%;" aria-hidden="true" required>
                        </select>
                    </div>
                </div><!--form-group-->

                <div class="row">
                  <label for="errorcityselect" class="col-md-2 col-form-label"></label>
                  <div class="col-10">
                    @error('city_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                  </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-2 col-form-label">@lang('Address')</label>

                    <div class="col-md-10">
                        <input type="text" wire:model.lazy="address" class="form-control" placeholder="{{ __('Address') }}" maxlength="100" />
                        @error('address') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="rfc" class="col-md-2 col-form-label">@lang('RFC')</label>

                    <div class="col-md-10">
                        <input type="text" step="any" wire:model.lazy="rfc" class="form-control" placeholder="{{ __('RFC') }}" maxlength="50" />
                        @error('rfc') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->
                <div class="form-group row">
                    <label for="comment" class="col-md-2 col-form-label">@lang('Comment')</label>

                    <div class="col-md-10">

                        <textarea class="form-control" wire:model.defer="comment" placeholder="{{ __('Comment') }}" id="comment" rows="3"></textarea>

                        @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div>
                </div><!--form-group-->

            </div>
            {{-- <livewire:backend.material-table /> --}}
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm float-right text-white" style="background-color: red;" type="submit">@lang('Save vendor')</button>
        </x-slot>

    </x-backend.card>
</form>


@push('middle-scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endpush

@push('after-scripts')

    <script>
      $(document).ready(function() {
        $('#cityselect').select2({
          placeholder: '@lang("Choose city")',
          // width: 'resolve',
          theme: 'bootstrap4',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.city.select') }}',
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
                                text: item.city + ', ' +item.capital + ', ' +item.country
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

          $('#cityselect').on('change', function (e) {
            var data = $('#cityselect').select2("val");
            @this.set('city_id', data);
          });

      });
    </script>

@endpush