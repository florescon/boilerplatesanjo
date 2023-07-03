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

                  <img class="card-img-top" src="{{ asset('/ga/img/banner.png' )}}" alt="Card image cap">

                  <div class="card-body">

                    <h4 class="card-title font-weight-bold mb-2">{{ $status_name }}</h4>

                    <br>
                    <br>

                    @if($status_to_add_users)
                      <livewire:backend.user.only-admins/>

                      @error('user') 

                        <div class="form-group row" wire:ignore>
                            <label for="userselect" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9" >
                              <span class="error" style="color: red;">{{ $message }}</span>
                            </div>
                        </div><!--form-group-->

                      @enderror

                    @endif

                    @if(!$previous_status)

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad orden</th>
                              <th  style="background-color:#5DADE2;" class="text-white">Producto Terminado</th>
                              <th>Cantidad conformado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="text-right">Total:</td>
                              <td>{{ $model->total_products }}</td>
                              <td></td>
                              <td></td>
                            </tr>
                            @foreach($model->products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                              <tr>
                                <td class="text-left">
                                  {{-- {{ $product->id }}  --}}
                                  {!! $product->product->full_name.'<br><strong>'.$product->comment.'</strong>' !!}
                                </td>
                                <td>{{ $product->quantity }}</td>

                                <td class="table-info"> 
                                  {{ $product->parent->stock ?? 0 }}
                                </td>
                                <td class="table-info"> 
                                  {{ $product->assign_process ?? 0 }}
                                </td>
                              </tr>
                            @endforeach
                            <tr>
                              <td class="text-right">Total:</td>
                              <td>{{ $model->total_products }}</td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td colspan="2"></td>
                              <td>
                                <button type="button" wire:click="save" class="btn btn-primary btn-sm">Hacer uso de Producto Terminado</button>
                              </td>
                              <td>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    @else

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad orden</th>
                              <th>Cantidad conformado</th>
                              <th>Agregar</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="text-right">Total:</td>
                              <td>{{ $model->total_products }}</td>
                              <td></td>
                              <td></td>
                            </tr>
                            @foreach($model->products->sortBy([['product.parent.name', 'asc'], ['product.color.name', 'asc'], ['product.size.sort', 'asc']]) as $product)
                              <tr>
                                <td class="text-left">
                                  {{-- {{ $product->id }}  --}}
                                  {!! $product->product->full_name.'<br><strong>'.$product->comment.'</strong>' !!}
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td class="table-info"> 
                                  {{ $product->assign_process ?? 0 }}
                                </td>
                                <td>
                                  <input type="number" 
                                      wire:model.defer="q.{{ $product->id }}.quantity"
                                      class="form-control"
                                      style="color: blue;" 
                                      placeholder="{{ $product->assign_process }}"
                                  >
                                  @error('q.'.$product->id.'.quantity') 
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
                              <td></td>
                              <td>
                                
                                <button type="button" wire:click="continue" class="btn btn-primary btn-sm">@lang('Continue')</button>

                              </td>
                            </tr>
                            <tr>
                              <td colspan="3"></td>
                              <td>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    @endif

                  </div>
                </div>
            </div>

            <div class="col-12 col-md-6">

              <p>
                <div class="btn-group" role="group" aria-label="Basic example">
                  @if($previous_status)
                    <a href="{{ route('admin.order.process', [$model->id, $previous_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $previous_status->name ?? null }}"> @lang('Previous workstation')</a>
                  @endif
                  @if($next_status)
                    <a href="{{ route('admin.order.process', [$model->id, $next_status->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" title="{{ $next_status->name ?? null }}"> @lang('Next workstation')</a>
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
                              #{{ $batch->parent_or_id.' - '.__('Batch') }}
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
                                </tr>
                                @endforeach
                                <tr>
                                  <td colspan="1" class="text-right">Total:</td>
                                  <td>{{ $batch->total_batch }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>

                          <div class="card-footer bg-transparent ">
                            <div class="row">
                              <div class="col-6 col-md-6 text-left">
                                @lang('Created at') {{ $batch->date_diff_for_humans_created }}
                                <br>
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