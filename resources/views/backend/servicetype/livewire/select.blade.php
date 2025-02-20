<div class="form-group row" wire:ignore>
    @if(!$clear)
        <label for="servicetypeselect" class="col-sm-3 col-form-label">@lang('Service Type')</label>
    @endif
    <div class="col-sm-9" >
		<select id="servicetypeselect"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#servicetypeselect').select2({
          placeholder: '@lang("Choose service type")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.servicetype.select') }}',
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

          $('#servicetypeselect').on('change', function (e) {
            var data = $('#servicetypeselect').select2("val");
            livewire.emit('serviceTypeItem', data)

          });

      });
    </script>

    <script>
        Livewire.on('clear-service-type', clear => {
            jQuery(document).ready(function () {
                $("#servicetypeselect").val('').trigger('change')
            });
        })
    </script>

@endpush