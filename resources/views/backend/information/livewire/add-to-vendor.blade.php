<div class="container py-3">
    <div class="row">
        <div class="mx-auto col-sm-8">
            <!-- form user info -->
            <div class="card shadow-lg">
                <div class="card-header p-4">
                    <h4 class="mb-0">{{ $status->name }} - Global</h4>
                </div>

                  <div class="card-body">
                    <form class="form" role="form" autocomplete="off">
                        @foreach ($groupedProducts as $parentId => $products)
                            @php
                                $rowspan = $products->count();
                            @endphp
                            @foreach ($products->sortBy([['productColorName', 'asc'], ['productSizeSort', 'asc'] ]) as $key => $product)

                            <div class="form-group row">
                                <label class="col-lg-8 col-form-label form-control-label">
                                    {{ $product['productParentCode'] }} 
                                    -
                                    <strong>{{ $product['productName'] }}</strong>
                                    {{ $product['productSizeName'] }}, {{ $product['productColorName'] }}
                                </label>
                                <div class="col-lg-2 text-center">
                                    {{ $product['productQuantity'] }}
                                </div>
                                <div class="col-lg-2">
                                    {{-- <input class="form-control text-center" type="text"> --}}
                                </div>
                            </div>

                            @endforeach
                        @endforeach
                  
                        @if($getProducts->count())
                            <div class="p-4 rounded" style="border: 3px solid #6529ff;">
                                @foreach ($getProducts as $getProduct)

                                    <div class="form-group row">
                                        <label class="col-lg-8 col-form-label form-control-label">
                                            {{ $getProduct->product->parent->code }}
                                            -
                                            {!! optional($getProduct->product)->full_name_and_vendor_link !!}
                                        </label>
                                        <div class="col-lg-2 text-center">
                                            {{ $getProduct->quantity }}
                                        </div>
                                        <div class="col-lg-2 text-center">
                                            <a wire:click="removeProduct({{ $getProduct->id }})" class="link link-dark-primary link-normal text-danger" style="cursor:pointer;" onclick="confirm('¿Seguro que desea eliminar este registro?') || event.stopImmediatePropagation()"><i class="fas fa-times text-c-blue m-l-10"></i></a> 
                                        </div>
                                    </div>

                                @endforeach


                                <a href="#" wire:click="clearAllProducts" onkeydown="return event.key != 'Enter';" class="btn btn-danger btn-sm mt-2">@lang('Clear products')</a>

                            </div>
                        @endif
             
                        <div class="form-group row pt-4">
                            <div class="col-lg-10 text-right">
                                <a type="reset" class="btn btn-secondary" href="{{ route('admin.information.status.show', $status->id) }}" value="Cancel">@lang('Cancel')</a>
                            </div>
                            <div class="col-lg-2 text-center">

                              <a type="button" target="_blank" href="{{ route('admin.information.status.pending_vendor_grouped', [$status->id, true]) }}" class="btn btn-outline-dark mb-4">Exportar</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /form user info -->
        </div>

        <div class="mx-auto col-sm-4">
            <!-- form user info -->
            <div class="card shadow-lg">
                <div class="card-header p-4">
                    <h4 class="mb-0">Search Product</h4>
                </div>

                <div class="card-body">
                    <a href="#!" data-toggle="modal" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Add product')</a>
                </div>
            </div>
            <!-- /form user info -->
        </div>

    </div>

    <livewire:backend.additional.search-products :typeSearch="'vendor'" branchIdSearch="0"/>

</div>
