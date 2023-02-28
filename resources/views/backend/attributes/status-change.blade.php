<div class="form-group row" wire:ignore>
    <div class="col-sm-12" >
		<select id="statuschange"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#statuschange').select2({
          placeholder: '@lang("Choose status")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          maximumInputLength: 5,
          ajax: {
                url: '{{ route('admin.status.select') }}',
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
            },
            escapeMarkup: function(m) { return m; }

          });

          $('#statuschange').on('change', function (e) {
            var data = $('#statuschange').select2("val");
            livewire.emit('selectedStatusOrderItem', data)
          });


      });
    </script>

    <script>
        Livewire.on('clear-status-order', clear => {
            jQuery(document).ready(function () {
                $("#statuschange").val('').trigger('change')
            });
        })
    </script>

@endpush