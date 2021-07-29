<div class="form-group row" wire:ignore>
    <div class="col-sm-12" >
		<select id="sizechange"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#sizechange').select2({
          placeholder: '@lang("Choose size")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
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

          $('#sizechange').on('change', function (e) {
            var data = $('#sizechange').select2("val");
            livewire.emit('selectedSizeItem', data)

          });


      });
    </script>
@endpush