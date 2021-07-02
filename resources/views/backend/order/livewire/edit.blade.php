@push('after-styles')
<style type="text/css">
</style>
@endpush

<x-backend.card>

  <x-slot name="header">
    @lang('Show order') #{{ $order_id }}
  </x-slot>

  <x-slot name="headerActions">
    <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
  </x-slot>
  <x-slot name="body">
    <div class="row ">

      <div class="col-12 col-sm-12 col-md-8" style="margin-top: 40px;">
        <div class="card card-product_not_hover card-flyer-without-hover">
          <div class="card-body">
            <h5 class="card-title">#{{ $model->id }}</h5>
            <p class="card-text">
              <div class="form-row ">
                
                <div class="col-md-3 mb-3">
                  <div class="visible-print text-left" wire:ignore>
                    {!! QrCode::size(100)->gradient(55, 115, 250, 105, 5, 70, 'radial')->generate(Request::url()); !!}
                    {{-- <p>Scan me to return to the original page.</p> --}}
                  </div>
                </div>

                <div class="col-md-9 mb-3">
                  <div class="row">
                    <div class="col-6 col-lg-6">
                      {{ optional($model->user)->name }}
                    </div>
                    <div class="col-6 col-lg-6" style="font-family:Arial, FontAwesome">
                      <a href="{{ route('admin.order.whereIs',$model->id) }}" style="color:#a20909ff;">
                        <em>
                          @lang('Where is products?')
                        </em> 
                        &#xf288;
                      </a>                                   
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-6 col-lg-6">
                      {{ $model->date_entered }}
                    </div>
                    <div class="col-6 col-lg-6">
                      {{ $model->created_at }}
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-12 col-lg-12">
                      {{ $model->comment }}
                    </div>
                  </div>
                </div>
              </div>

            </p>

            <div class="form-row">
              <div class="col-md-4 mb-3">
                {!! $model->last_status_order->status->name ?? '<span class="badge badge-secondary">'.__('undefined status').'</span>' !!}
                <div wire:loading wire:target="updateStatus" class="loading"></div>
              </div>
              <div class="col-md-4 mb-3">
                <a href="{{ route('admin.order.advanced', $model->id) }}" style="color:#1ab394;">
                  <p> Opciones avanzadas </p>
                </a>
              </div>
              <div class="col-md-4 mb-3 text-left">
                <a href="{{ route('admin.order.sub', $model->id) }}" style="color:purple;">
                  <p> Quiero asignar subordenes <i class="cil-library"></i></p> 
                </a>
  
                @php
                  $colors_counter = 0;
                  $colors = array(0=>"primary", 1=>"info", 2=>"secondary", 3=>"light");
                @endphp

                <div class="list-group">
                  @foreach($model->suborders as $suborder)
                    <a href="{{ route('admin.order.edit', $suborder->id) }}" class="list-group-item list-group-item-action flex-column align-items-start 
                      @if($colors_counter <= 3)
                        list-group-item-{{ $colors[$colors_counter] }}
                      @endif
                    ">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><strong> #{{ $suborder->id}} </strong> {{ optional($suborder->user)->name }}</h6>
                        <small>{{ $suborder->date_diff_for_humans }}</small>
                      </div>
                    </a>
                      <?php $colors_counter++; ?>
                  @endforeach
                </div>
              </div>
            </div>

            <a href="#" class="card-link text-dark"><i class="cil-print"></i>
              <ins>
                General
              </ins>
            </a>
            <a href="#" class="card-link"><i class="cil-print"></i>
              <ins>
                Productos
              </ins>
            </a>
            <a href="#" class="card-link text-warning"><i class="cil-print"></i>
              <ins>
                Materia prima
              </ins>
            </a>
          </div>
          <div class="card-footer text-muted text-center">
            {{ $model->date_diff_for_humans }}
          </div>
        </div>


        <div class="card card-edit card-product_not_hover card-flyer-without-hover">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                  <tr >
                    <th >Producto</th>
                    <th>Precio</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @php($totalmat = 0)
                  @foreach($model->product_order as $product)
                  <tr>
                    <td>{!! $product->product->full_name !!}</td>
                    <td class="text-center">${{ $product->price }}</td>
                    <td class="text-center">{{ $product->quantity }}</td>
                    <td class="text-center">${{ $product->total_by_product }}</td>
                  </tr>
                  @php($totalmat += $product->total_by_product)
                  @endforeach
                  <tr>
                    <td></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">{{ $model->total_products }}</td>
                    <td class="text-center">${{ $model->total_order }}</td>
                  </tr>

                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>


      <div class="col-12 col-md-4">
        <div class="row d-flex justify-content-center mt-70 mb-70">
          <div class="col-md-12">
            <div class="main-card mb-3 card card-edit">
              <div class="card-body">
                <h5 class="card-title">@lang('Status order')
                  <span class='badge badge-primary'>{{ $model->last_status_order->status->name ?? '' }}</span>
                </h5>
                <div wire:loading wire:target="updateStatus" class="loading">@lang('Wait 3 seconds')</div>
                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                  @foreach($statuses as $status)
                  <div class="vertical-timeline-item vertical-timeline-element">
                    <div> <span wire:click="updateStatus({{ $status->id }})" class="vertical-timeline-element-icon bounce-in"> <i  wire:loading.class.remove="badge-dot-xl badge-dot-xl2" class="badge badge-dot 
                      {{ $status->id == $lates_statusId ? 'badge-dot-xl2' : 'badge-dot-xl' }}
                      badge-primary"> </i> </span>
                      <div class="vertical-timeline-element-content bounce-in" style="{{ $status->id == $lates_statusId ? 'font-size: medium;' : '' }}">
                        <p class="timeline-title  {{ $status->id == $lates_statusId ? 'text-primary' : 'text-success' }}">{{ $status->name }}</p>
                        <p>{{ $status->description }}</p> 
                        @if($status->to_add_users)
                        <a href="{{ route('admin.order.assignments', [$model->id, $status->id]) }}">
                          <span class="vertical-timeline-element-date badge text-primary">
                            <i class="c-icon c-icon-4x cil-people"></i><i class="cil-plus"></i>
                          </span>
                        </a>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>

              <div class="card-body text-center">
                <a href="{{ route('admin.order.records', $model->id) }}" class="card-link">Ver registros de estados</a>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </x-slot>
  <x-slot name="footer">

    <x-utils.delete-button :text="__('Delete order')" :href="route('admin.order.destroy', $model->id)" />

    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>