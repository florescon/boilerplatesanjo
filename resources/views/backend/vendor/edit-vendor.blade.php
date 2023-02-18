@extends('backend.layouts.app')

@section('title', __('Edit vendor'))

@section('content')

    <x-forms.patch :action="route('admin.vendor.update', $vendor)">
        <x-backend.card>
            <x-slot name="header">
                {{ $vendor->email }} <strong style="color: red;"> {{ $vendor->name }} </strong>
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.vendor.index')" icon="fa fa-chevron-left" :text="__('Back')" />
            </x-slot>

            <x-slot name="body">
                <div>
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $vendor->name }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Short name')</label>

                        <div class="col-md-10">
                            <input type="text" name="short_name" class="form-control" placeholder="{{ __('Short name') }}" value="{{ old('short_name') ?? $vendor->short_name }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Email')</label>

                        <div class="col-md-10">
                            <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" value="{{ old('email') ?? $vendor->email }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="phone" class="col-md-2 col-form-label">@lang('Phone')</label>

                        <div class="col-md-10">
                            <input type="number" name="phone" class="form-control" placeholder="{{ __('Phone') }}" value="{{ old('phone') ?? $vendor->phone }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="city_id" class="col-md-2 col-form-label">@lang('City')</label>

                        <div class="col-md-5 text-center">
                            <x-utils.undefined :data="optional($vendor->city)->city"/>
                        </div>
                        <div class="col-md-5">
                            <select id="cityselect" name="city_id" id="city_id" class="custom-select" style="width: 100%;" aria-hidden="true" {{ !$vendor->city_id ? 'required' : '' }}>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="address" class="col-md-2 col-form-label">@lang('Address')</label>

                        <div class="col-md-10">
                            <input type="text" name="address" class="form-control" placeholder="{{ __('Address') }}" value="{{ old('address') ?? $vendor->address }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="rfc" class="col-md-2 col-form-label">@lang('RFC')</label>

                        <div class="col-md-10">
                            <input type="text" name="rfc" class="form-control" placeholder="{{ __('RFC') }}" value="{{ old('rfc') ?? $vendor->rfc }}" maxlength="50" />
                        </div>
                    </div><!--form-group-->


                    <div class="form-group row">
                        <label for="comment" class="col-md-2 col-form-label">@lang('Comment')</label>

                        <div class="col-md-10">
                            <input type="text" name="comment" class="form-control" placeholder="{{ __('Comment') }}" value="{{ old('comment') ?? $vendor->comment }}" maxlength="200" />

                        </div>
                    </div><!--form-group-->

                </div>
                {{-- <livewire:backend.vendor-table /> --}}
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm float-right text-white" style="background-color: red;" type="submit">@lang('Update vendor')</button>
            </x-slot>

        </x-backend.card>
    </x-forms.patch>

@endsection

@push('middle-scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endpush

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#cityselect').select2({
          placeholder: '@lang("Change city")',
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

      });
    </script>

@endpush
