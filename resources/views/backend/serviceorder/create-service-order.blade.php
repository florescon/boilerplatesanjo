<div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="text-right">
              <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary" >
               @lang('Go to edit order')
              </a>
          </div>

            <h4 class="card-title">@lang('Service Order')</h4>
            <p class="card-description"> @lang('Request') #{{ $order->id }} </p>

            @if($products->count())

                <div class="mb-4" wire:ignore>
                    <select id="imageselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
                    </select>
                </div>
                <div>
                    @error('image_id') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
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
                                            wire:model.defer="comment.{{ $record->product->id }}"
                                            wire:keydown.enter="save"
                                            class="form-control" 
                                            placeholder="{{ __('Comment') }}" maxlength="100"/>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                wire:model.defer="quantity.{{ $record->product->id }}.available"
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

                    <button class="btn btn-sm float-right text-white" style="background-color: blue;" wire:click="save">@lang('Create Service Order')</button>

                {{ $products->links() }}

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
                                text: item.title
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

          $('#imageselect').on('change', function (e) {
            var data = $('#imageselect').select2("val");
            @this.set('image_id', data);
          });

      });
    </script>

@endpush