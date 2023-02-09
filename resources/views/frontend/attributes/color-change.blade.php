<div class="form-group row" wire:ignore>
    <div class="col-sm-12" >
		<select id="colorchange"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#colorchange').select2({
          placeholder: '@lang("Choose color")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          maximumInputLength: 15,
          ajax: {
                url: '{{ route('frontend.colorSelect') }}',
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

          $('#colorchange').on('change', function (e) {
            var data = $('#colorchange').select2("val");
            livewire.emit('selectedColorItem', data)

          });


      });
    </script>

    <script>
        Livewire.on('clear-color', clear => {
            jQuery(document).ready(function () {
                $("#colorchange").val('').trigger('change')
            });
        })
    </script>

@endpush