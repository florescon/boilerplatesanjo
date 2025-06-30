<div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="text-right">
              <a href="{{ !$order->from_store ? route('admin.order.edit', $order->id) : route('admin.store.all.edit', $order->id) }}" class="btn btn-primary" >
               @lang('Go to edit order')
              </a>
          </div>

            <h4 class="card-title">Orden de Producción</h4>
            <p class="card-description"> @lang('Request') #{!! $order->folio_or_id !!} </p>

            @if($products->count())

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


