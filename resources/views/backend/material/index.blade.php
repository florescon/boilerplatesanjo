@extends('backend.layouts.app')

@section('title', __('Feedstock'))

@section('breadcrumb-links')
    @include('backend.material.includes.breadcrumb-links')
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/material.css') }}">
@endpush

@section('content')

    <x-backend.card>
        <x-slot name="header">
            <strong style="color: #0061f2;"> <kbd>@lang('Feedstock')</kbd> @lang('Sorted by'): @lang('Family') </strong>
        </x-slot>

        <x-slot name="headerActions">

            @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify-quantities'))
                <livewire:backend.material.modify-feedstock />
            @endif

            @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.create'))
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    style="color: orange;"
                    href="{{ route('admin.material.create') }}"
                    :text="__('Create feedstock')"
                />
            @endif

        </x-slot>

        <x-slot name="body">

            <table class="table mt-2 table-borderless">
              <thead>
                <tr>
                  <th scope="col" style="width: 33.33%;">
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify-quantities'))
                        <livewire:backend.material.select-color />
                    @endif
                  </th>
                  <th scope="col" style="width: 33.33%;">
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify-quantities'))
                        <livewire:backend.material.select-vendor />
                    @endif
                  </th>
                  <th scope="col" style="width: 33.33%;">
                    @if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.modify-quantities'))
                        <livewire:backend.material.select-family />
                    @endif
                  </th>
                </tr>
              </thead>
            </table>

            <livewire:backend.material-table />
        </x-slot>

        <x-slot name="footer">
          <footer class="footer mt-3">
              <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records_history') }}">Historial de stock entradas/salidas de materia prima</a>
                    </p>
                  </div>
                </div>
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records_history_group') }}">Historial de stock entradas/salidas de materia prima <strong><u>agrupado por fecha</u></strong></a>
                    </p>
                  </div>
                </div>
              </div>
          </footer>
          <footer class="footer mt-3">
              <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6 m-auto text-center">
                  <div>
                    <p> 
                      <a href="{{ route('admin.material.records') }}">Ir a registros de materia prima consumidos</a>
                    </p>
                  </div>
                </div>
              </div>
          </footer>
        </x-slot>
    </x-backend.card>

    <livewire:backend.material.massive-feedstocks />

    <livewire:backend.material.create-material />
    <livewire:backend.material.show-material />

    <livewire:backend.material.modal-stock-material />

@endsection

@push('after-scripts')

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
            Livewire.emit('postFamily', data)
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#familysecondselect').select2({
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

          $('#familysecondselect').on('change', function (e) {
            var data = $('#familysecondselect').select2("val");
            Livewire.emit('postFamilySecond', data)
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
            Livewire.emit('postVendor', data)
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#vendorsecondselect').select2({
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

          $('#vendorsecondselect').on('change', function (e) {
            var data = $('#vendorsecondselect').select2("val");
            Livewire.emit('postVendorSecond', data)
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorsecondselect').select2({
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

          $('#colorsecondselect').on('change', function (e) {
            var data = $('#colorsecondselect').select2("val");
            Livewire.emit('postColorSecond', data)
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselect').select2({
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
            var data = $('#colorselect').select2("val");
            Livewire.emit('postColor', data)
          });

      });
    </script>

    <script type="text/javascript">
      Livewire.on("materialStore", () => {
          $("#createMaterial").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("massiveStore", () => {
          $("#massiveFeedstocks").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("materialUpdate", () => {
          $("#updateStockModal").modal("hide");
      });
    </script>

    <script>
        Livewire.on('clear-family', clear => {
            jQuery(document).ready(function () {
                $("#familyselect").val('').trigger('change')
            });
        })
    </script>

    <script>
        Livewire.on('clear-vendor', clear => {
            jQuery(document).ready(function () {
                $("#vendorselect").val('').trigger('change')
            });
        })
    </script>

    <script>
        Livewire.on('clear-color', clear => {
            jQuery(document).ready(function () {
                $("#colorselect").val('').trigger('change')
            });
        })
    </script>

    <script>
        Livewire.on('clear-second-family', clear => {
            jQuery(document).ready(function () {
                $("#familysecondselect").val('').trigger('change')
            });
        })
    </script>

    <script>
        Livewire.on('clear-second-vendor', clear => {
            jQuery(document).ready(function () {
                $("#vendorsecondselect").val('').trigger('change')
            });
        })
    </script>

    <script>
        Livewire.on('clear-second-color', clear => {
            jQuery(document).ready(function () {
                $("#colorsecondselect").val('').trigger('change')
            });
        })
    </script>

@endpush
