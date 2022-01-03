@extends('backend.layouts.app')

@section('title', __('Edit feedstock'))

@section('content')

    <x-forms.patch :action="route('admin.material.update', $material)">
        <x-backend.card>
            <x-slot name="header">
                {{ $material->part_number }} <strong style="color: orange;"> {{ $material->name }} </strong>
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.material.index')"  icon="fa fa-chevron-left" :text="__('Back')" />
            </x-slot>

            <x-slot name="body">
                <div>
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Part number')</label>

                        <div class="col-md-10">
                            <input type="text" name="part_number" class="form-control" placeholder="{{ __('Part number') }}" value="{{ old('part_number') ?? $material->part_number }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $material->name }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Price')</label>

                        <div class="col-md-10">
                            <input type="text" name="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') ?? $material->price }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Unit')</label>

                        <div class="col-md-5 text-center">
                            <x-utils.undefined :data="optional($material->unit)->name"/>
                        </div>
                        <div class="col-md-5">
                            <select id="unitselect" name="unit_id" id="unit_id" class="custom-select" style="width: 100%;" aria-hidden="true" {{ !$material->unit_id ? 'required' : '' }}>
                            </select>
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Color')</label>

                        <div class="col-md-5 text-center">
                            <x-utils.undefined :data="optional($material->color)->name"/>
                        </div>
                        <div class="col-md-5">
                            <select id="colorselect" name="color_id" id="color_id" class="custom-select" style="width: 100%;" aria-hidden="true" {{ !$material->color_id ? 'required' : '' }}>
                            </select>
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Size_')</label>

                        <div class="col-md-5 text-center">
                            <x-utils.undefined :data="optional($material->size)->name"/>
                        </div>
                        <div class="col-md-5">
                            <select id="sizeselect" name="size_id" id="size_id" class="custom-select" style="width: 100%;" aria-hidden="true">
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Acquisition cost')</label>

                        <div class="col-md-10">
                            <input type="text" name="acquisition_cost" class="form-control" placeholder="{{ __('Acquisition cost') }}" value="{{ old('acquisition_cost') ?? $material->acquisition_cost }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('Description')</label>

                        <div class="col-md-10">
                            <input type="text" name="description" class="form-control" placeholder="{{ __('Description') }}" value="{{ old('description') ?? $material->description }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                </div>
                {{-- <livewire:backend.material-table /> --}}
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm float-right text-white" style="background-color: orange;" type="submit">@lang('Update Feedstock')</button>
            </x-slot>

        </x-backend.card>
    </x-forms.patch>

@endsection



@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#unitselect').select2({
          placeholder: '@lang("Change unit")',
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

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
          placeholder: '@lang("Change color")',
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

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
          placeholder: '@lang("Change size")',
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

      });
    </script>
@endpush
