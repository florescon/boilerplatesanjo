@inject('model', '\App\Domains\Auth\Models\User')

<form wire:submit.prevent="store">
    <x-backend.card>
        <x-slot name="header">
            @lang('Create departament')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.departament.index')" icon="fa fa-chevron-left" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
          <div>
            <div class="form-group row" wire:ignore>
                <label for="userselect" class="col-md-2 col-form-label">@lang('User')</label>

                <div class="col-md-10">
                  <select id="userselect" class="custom-select" style="width: 100%;" aria-hidden="true" required>
                  </select>
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
                <label for="email" class="col-md-2 col-form-label">@lang('Email')</label>

                <div class="col-md-10">
                    <input type="text" wire:model.lazy="email" class="form-control" placeholder="{{ __('Email') }}" maxlength="100" />
                    @error('email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="comment" class="col-md-2 col-form-label">@lang('Comment')</label>

                <div class="col-md-10">
                    <input type="text" wire:model.lazy="comment" class="form-control" placeholder="{{ __('Comment') }}" maxlength="100" />
                    @error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

            <div class="form-group row">
                <label for="phone" class="col-md-2 col-form-label">@lang('Phone')</label>

                <div class="col-md-10">
                    <input type="text" wire:model.lazy="phone" class="form-control" placeholder="{{ __('Phone') }}" maxlength="100" />
                    @error('phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->

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
                    <input type="text" wire:model.lazy="rfc" class="form-control" placeholder="{{ __('RFC') }}" maxlength="100" />
                    @error('rfc') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>
            </div><!--form-group-->


            <div class="form-group row">
                <label for="rfc" class="col-md-2 col-form-label">@lang('Type price')</label>

                <div class="col-md-10">
                  <select wire:model.lazy="type_price" name="type_price" class="form-control">
                      <option value="{{ $model::PRICE_RETAIL }}">@lang('Retail price')</option>
                      <option value="{{ $model::PRICE_AVERAGE_WHOLESALE }}">@lang('Average wholesale price')</option>
                      <option value="{{ $model::PRICE_WHOLESALE }}">@lang('Wholesale price')</option>
                  </select>
                </div>
            </div><!--form-group-->

          </div>
        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm float-right text-dark" style="background-color: #BEFFDF;" type="submit">@lang('Save departament')</button>
        </x-slot>

    </x-backend.card>
</form>


@push('middle-scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endpush

@push('after-scripts')

  <script>
    $(document).ready(function() {
      $('#userselect').select2({
        placeholder: '@lang("Choose user")',
        // width: 'resolve',
        theme: 'bootstrap4',
        // allowClear: true,
        ajax: {
              url: '{{ route('admin.users.onlyUsers') }}',
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

        $('#userselect').on('change', function (e) {
          var data = $('#userselect').select2("val");
          @this.set('user_id', data);
        });

    });
  </script>

@endpush