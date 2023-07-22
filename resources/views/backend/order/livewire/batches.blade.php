<x-backend.card>
    <x-slot name="header">
        @lang('Show batches') - {{ $status_name }}
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.order.edit', $order_id)" :text="__('Go to edit order')" />

        <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

        <div class="row ">
            <div class="col-16 col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h6 class="card-title mb-2"> <strong>@lang('Order') #{!! $model->folio_or_id !!}</strong> <em>{{ optional($model->user)->real_name }}</em>
                      <p class="mt-2">{{ $model->info_customer }}</p>
                      <p>{{ $model->comment }}</p>
                    </h6>
                  </div>
                </div>

                <div class="card card-edit card-product_not_hover card-flyer-without-hover">
                  <img class="card-img-top" src="{{ asset('/ga/img/lotes2.jpg' )}}" alt="Card image cap">

                  <div class="card-body">
              
                    <h4 class="card-title font-weight-bold mb-2" style="margin-top: -60px;">{{ $status_name }}</h4>

                    <br><br>

                    <livewire:backend.user.only-admins/>
                    
                    @error('user') 

                      <div class="form-group row" wire:ignore>
                          <label for="userselect" class="col-sm-3 col-form-label"></label>
                          <div class="col-sm-9" >
                            <span class="error" style="color: red;">{{ $message }}</span>
                          </div>
                      </div><!--form-group-->

                    @enderror

                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label">@lang('Date') <em>(Por defecto hoy)</em></label>
                        <div class="col-sm-9" >
                          <input wire:model="date" type="date" class="form-control"/>
                        </div>
                        @error('date') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                    </div><!--form-group-->

                    @if(!$previous_status)

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad orden</th>
                              <th  style="background-color:#5DADE2;" class="text-white">@lang('To batched')</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($model->products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                              <tr>
                                <td class="text-left">
                                  {{-- {{ $product->id }}  --}}
                                  {!! $product->product->full_name.'<br><strong>'.$product->comment.'</strong>' !!}
                                </td>
                                <td>{{ $product->quantity }}</td>

                                <td class="table-info"> 
                                    <input type="number" 
                                        wire:model.debounce.700ms="quantity.{{ $product->id }}.available"
                                        class="form-control text-center"
                                        style="color: red;"
                                        placeholder="{{ $product->available_batch }}"
                                    >
                                    @error('quantity.'.$product->id.'.available') 
                                      <span class="error" style="color: red;">
                                        <p>@lang('Check the quantity')</p>
                                      </span> 
                                    @enderror
                                </td>
                              </tr>
                            @endforeach
                            <tr>
                              <td class="text-right">Total:</td>
                              <td>{{ $model->total_products }}</td>
                              <td>
                                {{ $model->total_batch_pending }}
                                @if($quantity)
                                  {{-- <div style="border-width: 2px; border-style: dashed; border-color: red; "> @lang('Captured'): <strong>{{ $sumQuantity }}</strong> </div> --}}
                                  {{-- <livewire:backend.components.sum-captured /> --}}
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2"></td>
                              <td>
                                <button type="button" wire:click="save" class="btn btn-primary btn-sm" wire:loading.attr="disabled">@lang('Create batch')
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    @else
                      @foreach($model->batches as $batch)
                        @if($batch->status_id === $previous_status->id)

                          #{{ $batch->parent_or_id }} -- {{ $batch->personal->real_name }}

                          <table class="table table-dark">
                            <thead>
                              <tr>
                                <th scope="col">@lang('Product')</th>
                                <th scope="col">@lang('Quantity')</th>
                                <th scope="col">@lang('Received')</th>
                                <th scope="col">{{ $status_name }}</th>
                                <th scope="col">@lang('Available')</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($batch->batch_product->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                                <tr>
                                  <td>{!! $product->product->full_name !!}</td>
                                  <td>{{ $product->quantity }}</td>
                                  <td>{{ $product->quantity_received }}</td>
                                  <td>{{ $product->quantity_children }}</td>
                                  <td> 
                                    <input type="number" 
                                        wire:model.debounce.700ms="q.{{ $product->id }}.available"
                                        wire:keydown.enter="continue({{ $batch->id }})" 
                                        class="form-control text-center"
                                        style="color: red;" 
                                        placeholder="{{ $product->available }}"
                                    >
                                    @error('q.'.$product->id.'.available') 
                                      <span class="error" style="color: red;">
                                        <p>@lang('Check the quantity')</p>
                                      </span> 
                                    @enderror
                                  </td>
                                </tr>
                              @endforeach
                              <tr>
                                <td class="text-right">Total:</td>
                                <td>{{ $batch->total_batch }}</td>
                                <td>{{ $batch->total_batch_received }}</td>
                                <td>{{ $batch->total_batched }}</td>
                                <td>
                                  @if($q)
                                    {{-- <div style="border-width: 2px; border-style: dashed; border-color: red; "> @lang('Captured'): <strong>{{ $sumQuantity }}</strong> </div> --}}
                                    {{-- <livewire:backend.components.sum-captured /> --}}
                                  @endif
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4"></td>
                                <td class="text-center">
                                  <button type="button" wire:click="continue({{ $batch->id }})" class="btn btn-primary btn-sm">@lang('Continue')</button>
                                </td>
                            </tbody>
                          </table>
                        @endif
                      @endforeach
                    @endif

                  </div>
                </div>
            </div>

            <div class="col-12 col-md-6">

              <p>
                <div class="btn-group" role="group" aria-label="Basic example">
                  @if($previous_status)
                    <a href="{{ route('admin.order.batches', [$model->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}"><i class="c-icon  c-icon-4x cil-people"></i> @lang('Previous status')</a>
                  @endif

                  @if($next_status)
                    <a href="{{ route('admin.order.batches', [$model->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}"><i class="c-icon  c-icon-4x cil-people"></i> @lang('Next status')</a>
                  @endif
                </div>
              </p>
              <br>

              <div class="row">
                <div class="col-md-12 col-sm-6">
                  @foreach($model->batches as $batch)

                  @if($batch->status_id === $status_id)
                    <div class="card card-assignment card-block border-primary">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-md-6 col-sm-3">
                            {!! optional($batch->personal)->name ? '<div class="badge bg-danger text-wrap text-white" style="width: 6rem;">'.optional($batch->personal)->name
                            .'
                              
                            </div>' : '
                            <span class="badge badge-success">Stock'. appName().'</span>
                            ' !!}
                            #{{ $batch->parent_or_id.' - '.$batch->status->name }}
                          </div>
                          <div class="col-md-4 col-sm-3">
                              <a href="{{ route('admin.order.ticket_batch', [$order_id, $batch->id]) }}" class="card-link" target="_blank"><i class="cil-print"></i> Ticket </a>
                          </div>
                          <div class="col-md-2 col-sm-6 text-right">
                            {{-- <a href="{{ url('/') }}">
                              <i class="cil-x-circle"></i>
                            </a> --}}

                          <x-utils.delete-button :text="__('')" :href="route('admin.batch.destroy', $batch->id)" />

                          </div>
                        </div>
                      </div>
                      <div class="card-body ">

                        <div class="row mt-3 mb-5">
                          <div class="col-12 col-lg-12">
                              
                              @if($batch->date_entered)
                                @lang('Date'): <strong class="text-primary">{{ $batch->date_entered->format('d-m-Y') }}</strong>
                              @endif

                            <livewire:backend.components.edit-field :model="'\App\Models\Batch'" :entity="$batch" :field="'comment'" :key="'tickets'.$batch->id"/>
                          </div>
                        </div>

                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover text-center">
                            <thead>
                              <tr>
                                <th>Producto</th>
                                <th>Asignado</th>
                                <th>@lang('Received amount')</th>
                                <th>@lang('To receive')</th>
                              </tr>
                            </thead>
                            <tbody>

                              @foreach($batch->batch_product->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $batch_product)
                                <tr>
                                  <td class="text-left">
                                    {!! $batch_product->product->full_name !!}
                                    <div class="small text-muted">@lang('Last Updated'): {{ $batch_product->updated_at }}</div>
                                  </td>
                                  <td> 
                                    {{ $batch_product->quantity }}
                                  </td>
                                  <td>
                                    {{ $batch_product->quantity_received }}
                                  </td>
                                  <td>
                                    <livewire:backend.order.batch-amount-received :batch="$batch_product" :last_status="$next_status->id ?? null" :key="$batch_product->id" />
                                  </td>
                                </tr>
                              @endforeach
                              <tr>
                                <td colspan="1" class="text-right">Total:</td>
                                <td>{{ $batch->total_batch }}</td>
                                <td>{{ $batch->total_batch_received }}</td>
                                <td></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>

                        <div class="card-footer bg-transparent ">
                          <div class="row">
                            <div class="col-6 col-md-6 text-left">
                              @lang('Created at') {{ $batch->date_diff_for_humans_created }}
                              <br>
                              <div class="form-inline mt-3">
                                <div class="form-group ">
                                  <input wire:model="date_entered" class="form-control" type="date">

                                @if($date_entered)
                                  <button type="button" wire:click="saveDate({{ $batch->id }})" class="btn btn-primary"> 
                                    @lang('Save')
                                  </button>
                                @endif
                                </div>

                              </div>
                            </div>
                            <div class="col-6 col-md-6 text-right">
                              <a wire:click="outputUpdateAll({{ $batch->id }})" class="card-link text-right" wire:loading.remove>
                                <u>Marcar que se recibieron todos los productos de este avance</u>
                              </a>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  @endif
                  @endforeach

                </div>
              </div>

            </div>
        </div>
    </x-slot>
</x-backend.card>


@push('after-scripts')
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
</script>
@endpush