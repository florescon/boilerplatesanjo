<form wire:submit.prevent="store">
  <x-backend.card>

      <x-slot name="body">
        <div  wire:ignore>
          <select id="productselect" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true" >
          </select>
        </div>
      </x-slot>

      @if($product_id)
        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Add product')</button>
        </x-slot>
      @endif

  </x-backend.card>

</form>

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#productselect').select2({
          ajax: {
                url: '{{ route('admin.product.selectgroup') }}',
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
                      results: data.products.map(function (item) {
                          return {
                              text: item.name,
                              children: $.map(item.children, function(child, title){
                                  return {
                                      id: child.id,
                                      title: item.name,
                                      text: child.id 
                                  }
                              })

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

            placeholder: '@lang("Choose products")',
            width: 'resolve',
            theme: 'bootstrap4',
            allowClear: true,
            multiple: true,

            templateSelection: function (data) {
              if (data.id === '') { // adjust for custom placeholder values
                return '@lang('Product')';
              }

              return data.title.bold() + '  ' + data.text;
            },

            escapeMarkup: function(m) { return m; },

          });

          $('#productselect').on('change', function (e) {
            var data = $('#productselect').select2("val");
            @this.set('product_id', data);
          });

      });
    </script>
@endpush