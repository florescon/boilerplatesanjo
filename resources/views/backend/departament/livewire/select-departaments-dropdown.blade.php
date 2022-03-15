<div class="form-group row" wire:ignore>
    @if(!$clear)
        <label for="departamentselect" class="col-sm-3 col-form-label">@lang('Departament')</label>
    @endif
    <div class="{{ $clear ? 'col-sm-12 text-center' : 'col-sm-9' }}" >
        <select id="departamentselect"  class="custom-select" style="width: 100%;" aria-hidden="true">
        </select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#departamentselect').select2({
          placeholder: '@lang("Choose departament")',
          dropdownParent: $("#reasignDepartament"),
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.departament.select') }}',
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

          $('#departamentselect').on('change', function (e) {
            var data = $('#departamentselect').select2("val");
            livewire.emit('selectedDeparament', data)

          });

      });
    </script>
@endpush