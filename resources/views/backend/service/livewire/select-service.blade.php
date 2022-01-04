<div class="form-group row" wire:ignore>
    @if(!$clear)
        <label for="serviceselect" class="col-sm-3 col-form-label">@lang('Service')</label>
    @endif
    <div class="{{ $clear ? 'col-sm-12 text-center' : 'col-sm-9' }}" >
        <select id="serviceselect"  class="custom-select" style="width: 100%;" aria-hidden="true">
        </select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#serviceselect').select2({
          dropdownParent: $("#addService"),
          placeholder: '@lang("Choose service")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.service.select') }}',
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

          $('#serviceselect').on('change', function (e) {
            var data = $('#serviceselect').select2("val");
            livewire.emit('selectedService', data)

          });

      });
    </script>
@endpush