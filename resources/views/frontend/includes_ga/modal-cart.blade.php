        <div class="modal fade modal-cart {{ !$cartTotal && !$cartTotalOrder ? 'modal-small' : '' }}" id="modalCart" tabindex="-1" role="dialog" aria-labelledby="modalCart" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-dark-blue">
                    <div class="modal-body z-bigger">
                        @if($cartTotal || $cartTotalOrder)
                        <div class="container-fluid">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="uil uil-multiply"></i>
                            </button>
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3 pb-3 color-light-2">@lang('Shopping cart')</h5>
                                </div>
                            </div>

                            @foreach($cart as $product)
                            <div class="row mt-3">
                                <div class="col-sm-4 img-wrap mb-2 mb-sm-0">
                                    <img class="border-4" src="{{ asset('/storage/' . $product->parent->file_name) }}" alt="shop cart">
                                </div>
                                <div class="col align-self-center">
                                    <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="link link-gray mr-3" data-hover="{{ __('Go to view product') }}">{!! $product->full_name !!}</a> 
                                    <p class="mb-0 lead font-weight-500 color-gray">${{ $product->price }}</p>
                                </div>
                                <div class="col-auto align-self-center">
                                    <p class="mb-0 lead color-primary">x {{ $product->amount }}</p>
                                </div>
                            </div>
                            @endforeach
                            <div class="row mt-5">
                                @if($cartTotal)
                                    <div class="col-sm align-self-center">
                                        <p class="mb-0 lead color-light-2 size-24 font-weight-500">@lang('Products order'): <span class="font-weight-bold">{{ $cartTotal }}</span></p>
                                    </div>
                                @endif

                                @if($cartTotal > 4)
                                <div class="col-sm text-sm-right mt-3 mt-sm-0">
                                    <div class="alert alert-danger" role="alert">
                                      Tienes {{ $cartTotal }} productos para orden pero solo se muestra un limite de 4. Ir a ver carrito para ver listado completo
                                    </div>
                                </div>
                                @endif
                            </div>


                            <div class="row mt-5">
                                @if($cartTotalOrder)
                                <div class="col-sm align-self-center">
                                    <p class="mb-0 lead color-light-2 size-24 font-weight-500">@lang('Products sale'): <span class="font-weight-bold">{{ $cartTotalOrder }}</span></p>
                                </div>
                                @endif
                                <div class="col-sm text-sm-right mt-3 mt-sm-0">
                                    <a href="{{ route('frontend.cart.index') }}" class="btn btn-primary-gradient animsition-link"><i class="uil uil-arrow-circle-right size-22 mr-2"></i>@lang('View cart')</a>
                                </div>
                            </div>
                        </div>
                        @else
                            <div class="modal-body z-bigger">
                                <div class="container-fluid">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="uil uil-multiply"></i>
                                    </button>
                                    <div class="row justify-content-center">
                                        <div class="col-7 col-sm-6 col-lg-5 text-center img-wrap mb-5">
                                            <img src="{{ asset('/ga/img/cart-empty.png')}}" alt="modal">
                                        </div>
                                        <div class="col-12 text-center">
                                            <h5 class="mb-3 text-white">@lang('Your Cart is Empty')!</h5>
                                        </div>
                                        <div class="col-12 text-center">
                                            <p class="mb-0">@lang('Add something to make you happy').</p>
                                        </div>
                                        <div class="col-12 text-center mt-5">
                                            <a role="button" href="{{ route('frontend.shop.index') }}" class="btn btn-primary mx-1">@lang('Go to products')</a>  
                                       </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
