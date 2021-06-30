@push('after-styles')
    <style type="text/css">
        body {
            background: linear-gradient(45deg, #ffa256, #4432a7, #1bae68);
            background-size: 600% 600%;

            -webkit-animation: AnimationName 47s ease infinite;
            -moz-animation: AnimationName 47s ease infinite;
            -o-animation: AnimationName 47s ease infinite;
            animation: AnimationName 47s ease infinite;
        }

        @-webkit-keyframes AnimationName {
            0%{background-position:99% 0%}
            50%{background-position:2% 100%}
            100%{background-position:99% 0%}
        }
        @-moz-keyframes AnimationName {
            0%{background-position:99% 0%}
            50%{background-position:2% 100%}
            100%{background-position:99% 0%}
        }
        @-o-keyframes AnimationName {
            0%{background-position:99% 0%}
            50%{background-position:2% 100%}
            100%{background-position:99% 0%}
        }
        @keyframes AnimationName {
            0%{background-position:99% 0%}
            50%{background-position:2% 100%}
            100%{background-position:99% 0%}
        }
    </style>
@endpush

<x-backend.card>

	<x-slot name="header">
        @lang('Move between stocks')
 	</x-slot>

    <x-slot name="headerActions">

	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" />

        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

    <x-slot name="body">
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center mb-4">
                        <h2 class="heading-section">{{ $product_name }}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="h5 mb-4 text-center">@lang('Total stock'): {{ $model->getTotalStock() }}</h3>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead class="border-bottom border-start">
                                    <tr>
                                      <th scope="col">@lang('Action')</th>
                                      <th scope="col">@lang('Stock')</th>
                                      <th scope="col">@lang('Revision stock')</th>
                                      <th scope="col">@lang('Store stock')</th>
                                  </tr>
                                </thead>
                                
                                <tbody>
                                    <tr class="">
                                      <th scope="row"><span class="badge bg-success text-white">@lang('Move')</span></th>
                                      <td>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" wire:model="moveToStock" id="stock" name="stock" class="custom-control-input">
                                            <label class="custom-control-label" for="stock"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" wire:model="moveToRevisionStock" id="revisionstock" name="revisionstock" class="custom-control-input">
                                            <label class="custom-control-label" for="revisionstock"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" wire:model="moveToStoreStock" id="storestock" name="storestock" class="custom-control-input">
                                            <label class="custom-control-label" for="storestock"></label>
                                        </div>
                                    </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="table-wrap">
                            <div class="table-responsive">
                            <table class="table myaccordion table-hover" id="accordion">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Stock {{ '('. $model->getTotStock().')' }}</th>
                                        <th>@lang('Revision stock') {{ '('. $model->getTotStockRev().')' }}</th>
                                        <th>@lang('Store stock') {{ '('. $model->getTotStockStore().')' }}</th>
                                        <th class="table-secondary">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parents as $product)
                                        <tr data-toggle="collapse" data-target="#collapse{{ $product['id'] }}" aria-expanded="false" aria-controls="collapse{{ $product['id'] }}" class="collapsed">
                                            <th scope="row">{{ $product['id'] }}</th>
                                            <td>
                                                <span class="mb-0 text-sm">{!! '<strong>' .$product['parent']['name'].' </strong> ('.optional($product['color'])['name'].'  '.optional($product['size'])['name'].') ' !!}</span>
                                            </td>
                                            <td class="{{ $moveToStock ? 'table-success pulsate' : '' }}">
                                                {{ $product['stock'] }} 
                                            </td>
                                            <td class="{{ $moveToRevisionStock ? 'table-success pulsate' : '' }}">
                                                {{ $product['stock_revision'] }} 
                                            </td>
                                            <td class="{{ $moveToStoreStock ? 'table-success pulsate' : '' }}">
                                                {{ $product['stock_store'] }} 
                                            </td>
                                            <td class="table-secondary">
                                                {{ $model->getTotalStockbyID($product['id']) }}
                                            </td>
                                        </tr>
                                        <tr id="collapse{{ $product['id'] }}" class="collapse acc" data-parent="#accordion" wire:ignore.self>
                                            <td></td>
                                            <td >
                                                @if($moveToStock)
                                                    <p class="font-italic"><u>Se movera a @lang('Stock')</u></p>
                                                @elseif($moveToRevisionStock)
                                                    <p class="font-italic"><u>Se movera a @lang('Revision stock')</u></p>
                                                @elseif($moveToStoreStock)
                                                    <p class="font-italic"><u>Se movera a @lang('Store stock')</u></p>
                                                @else
                                                    <div class="badge badge-primary text-wrap " style="width: 6rem;">
                                                      Seleccione stock de destino
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($moveToStoreStock || $moveToRevisionStock)
                                                    <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model="inputmove" 
                                                    @if($moveToStoreStock)
                                                        wire:keydown.enter="moveToStore({{ $product['id'] }})"
                                                    @elseif($moveToRevisionStock)
                                                        wire:keydown.enter="moveToRevision({{ $product['id'] }})"
                                                    @endif                                                     
                                                    type="number" min="1" placeholder="+" max="{{ $product['stock'] }}">

                                                    @error('inputmove') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                                @elseif($moveToStock)
                                                    <i class="cil-arrow-top" style="color:blue"></i>
                                                    <i class="cil-arrow-top" style="color:blue" ></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($moveToStock || $moveToStoreStock)
                                                    <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model="inputmovefromRevision" 
                                                    @if($moveToStoreStock)
                                                        wire:keydown.enter="moveToStore({{ $product['id'] }})" 
                                                    @elseif($moveToStock)
                                                        wire:keydown.enter="moveToStock({{ $product['id'] }})" 
                                                    @endif
                                                    type="number" min="1" placeholder="+">

                                                    @error('inputmovefromRevision') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                                @elseif($moveToRevisionStock)
                                                    <i class="cil-arrow-top" style="color:blue"></i>
                                                    <i class="cil-arrow-top" style="color:blue" ></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($moveToStock || $moveToRevisionStock)
                                                    <input class="form-control form-control-sm is-valid" style="background-image: none; padding-right: inherit;" wire:model="inputmovefromStore" 
                                                    @if($moveToStock)
                                                        wire:keydown.enter="moveToStock({{ $product['id'] }})" 
                                                    @elseif($moveToRevisionStock)
                                                        wire:keydown.enter="moveToRevision({{ $product['id'] }})" 
                                                    @endif
                                                    type="number" min="1" placeholder="+">

                                                    @error('inputmovefromStore') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                                @elseif($moveToStoreStock)
                                                    <i class="cil-arrow-top" style="color:blue"></i>
                                                    <i class="cil-arrow-top" style="color:blue" ></i>
                                                @endif
                                            </td>
                                            <td>
                                            </td>
                                        </tr>  
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</x-slot>

</x-backend.card>