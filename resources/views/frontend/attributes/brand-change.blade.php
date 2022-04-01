<div class="form-group row" wire:ignore>
    <div class="col-sm-12" >
		<select id="brandchange"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#brandchange').select2({
          placeholder: '@lang("Choose brand")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          maximumInputLength: 5,
          ajax: {
                url: '{{ route('frontend.brandSelect') }}',
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
                                text: item.name.toLowerCase()
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

          $('#brandchange').on('change', function (e) {
            var data = $('#brandchange').select2("val");
            livewire.emit('selectedBrandItem', data)
          });


      });
    </script>
@endpush