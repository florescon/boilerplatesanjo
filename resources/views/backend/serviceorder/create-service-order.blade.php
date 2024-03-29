<div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="text-right">
              <a href="{{ !$order->from_store ? route('admin.order.edit', $order->id) : route('admin.store.all.edit', $order->id) }}" class="btn btn-primary" >
               @lang('Go to edit order')
              </a>
          </div>

            <h4 class="card-title">@lang('Service Order')</h4>
            <p class="card-description"> @lang('Request') #{!! $order->folio_or_id !!} </p>

            @if($products->count())

                <div class="mb-4" wire:ignore>
                    <select id="servicetypeselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>
                <div>
                    @error('service_type_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>

                <div class="mb-4" wire:ignore>
                    <select id="imageselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>
                <div>
                    @error('image_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>

                <div class="mb-4">
                    <input type="text" class="form-control text-center" wire:model="dimensions" placeholder="{{ __('Dimensions') }}">
                    @error('dimensions') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>

                <div class="mb-4">
                    <input type="text" class="form-control text-center" wire:model="file_text" placeholder="{{ __('File') }}">
                    @error('file_text') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>

                <div class="mb-4">
                    <input type="text" class="form-control text-center" wire:model="comment_general" placeholder="{{ __('General comment') }}">
                    @error('comment_general') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                {{-- <th></th> --}}
                                <th>@lang('Product')</th>
                                <th>@lang('Quantity')</th>
                                <th width="40%">@lang('Comment')</th>
                                <th>@lang('Assignment')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $record)
                                <tr>
                                    <td>{!! $record->product->full_name !!}</td>
                                    <td>{{ $record->quantity }}</td>
                                    <td>
                                        <input type="text" 
                                        wire:model.defer="comment.{{ $record->id }}"
                                        wire:keydown.enter="save"
                                        class="form-control" 
                                        placeholder="{{ __('Comment') }}" maxlength="260"/>

                                        @error('comment.'.$record->id) 
                                          <span class="error" style="color: red;">
                                            <p>@lang('Max: 255')</p>
                                          </span> 
                                        @enderror

                                    </td>
                                    <td>
                                        <input type="number"
                                            wire:model.defer="quantity.{{ $record->id }}.available"
                                            wire:keydown.enter="save"
                                            class="form-control"
                                            style="color: blue;" 
                                            {{-- placeholder="{{ $record->available_assignments }}" --}}
                                        >
                                        @error('quantity.'.$record->id.'.available') 
                                          <span class="error" style="color: red;">
                                            <p>@lang('Check the quantity')</p>
                                          </span> 
                                        @enderror

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                                
                </div>

                <button class="btn btn-sm float-right text-white" style="background-color: blue;" wire:click="save">@lang('Create Service Order') 🔥</button>

                {{-- {{ $products->links() }} --}}

            @else
                <em>Sin registros de productos y/o servicios</em>
            @endif

        </div>
    </div>
</div>


@push('after-scripts')

    <script>
      $(document).ready(function() {
        $('#imageselect').select2({
          placeholder: '@lang("Choose image")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.image.select') }}',
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
                                text: '<img src="/storage/' + item.image + '" style="width: 60px; height: auto" title="'+ item.title +'" />&nbsp;&nbsp;&nbsp<h4 style="display:inline;">' + item.title + '</h4>'
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

          $('#imageselect').on('change', function (e) {
            var data = $('#imageselect').select2("val");
            @this.set('image_id', data);
          });

      });
    </script>

    <script>
        Livewire.on('clear-image', clear => {
            jQuery(document).ready(function () {
                $("#imageselect").val('').trigger('change')
            });
        })
    </script>


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
            @this.set('service_type_id', data);
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