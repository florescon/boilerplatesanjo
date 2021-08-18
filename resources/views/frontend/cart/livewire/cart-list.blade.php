@if(count($cart['products']) > 0 || count($cart['products_sale']))
    <div class="row justify-content-center">
        <div class="col-12 text-lg-right mt-3" >
            <div class="row" >
                @if($cart['products_sale'])

                <div class="col">

                <h5 class="mb-3 pb-3 color-light-1 text-center">@lang('Cart sale')</h5>

                <div class="row">
                    <div class="col-auto mr-auto">
                        <p wire:click="clearCartSale" style="cursor:pointer;" class="mb-3 text-muted text-center"><i class="uil uil-multiply size-11"></i> @lang('Clear cart sale')</p>
                    </div>
                </div>

                    {{-- @json($cart['products_sale']) --}}

                    @foreach($cart['products_sale'] as $product)
                    <div class="section bg-light-2 border-4 py-5 py-sm-3 px-4 alert fade show mb-2" role="alert">
                        <div class="row">
                            <div class="col-sm-auto align-self-center text-right mt-n4 mt-sm-0">
                                <a wire:click="removeFromCartListSale({{ $product->id }})" class="link link-dark-primary link-normal" ><i class="uil uil-multiply size-22"></i></a> 
                            </div>
                            <div class="w-100 d-block d-sm-none"></div>
                            <div class="col-auto align-self-center shop-cart-img">
                                <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="animsition-link">
                                    @if($product->parent->file_name)
                                        <img class="border-4" src=" {{ asset('/storage/' . $product->parent->file_name) }}" alt="product" width="80" >
                                    @else
                                        <img  class="border-4"src="{{ asset('/img/ga/not0.png')}}" alt="{{ $product->parent->name }}" width="80">
                                    @endif
                                </a> 
                            </div>
                            <div class="w-100 d-block d-sm-none"></div>
                            <div class="col align-self-center mt-3 mt-sm-0">
                                <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="link link-dark-primary link-normal mb-1 animsition-link">
                                    {{ $product->parent->name }}
                                </a> 
                                <p class="mb-0 size-14">
                                    @lang('Color'): {{ $product->color_name }}
                                </p> 
                                <p class="mb-0 size-14">
                                    @lang('Size'): {{ $product->size_name }}
                                </p> 
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col align-self-center mt-4 mt-lg-0">
                                <h6 class="mb-0 color-secondary font-weight-700">
                                    ${{!is_null($product->price) || $product->price != 0 ? 
                                    $product->price : $product->parent->price }}
                                </h6> 
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col-auto align-self-center mt-4 mt-lg-0">
                                <livewire:frontend.cart.cart-list-update-form :item="$product" :key="$product->id" :typeCart="'products_sale'"/>
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col align-self-center text-lg-right mt-4 mt-lg-0">
                                <h6 class="mb-0 font-weight-700">
                                    {{-- $420.00 --}}
                                    @json($product->amount)
                                </h6> 
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                <!-- <div class="w-100 d-block d-md-none"></div> -->
                @if($cart['products'])
                <div class="col " >

                    <h5 class="mb-3 pb-3 color-light-1 text-center">@lang('Cart order')</h5>

                    <div class="row">
                        <div class="col-auto mr-auto">
                             <p wire:click="clearCartOrder" style="cursor:pointer;" class="mb-3 text-muted text-center"><i class="uil uil-multiply size-11"></i> @lang('Clear cart order')</p>
                        </div>
                    </div>


                    @foreach($cart['products'] as $product)
                    <div class="section bg-light-2 border-4 py-5 py-sm-3 px-4 alert fade show mb-2" role="alert">
                        <div class="row">
                            <div class="col-sm-auto align-self-center text-right mt-n4 mt-sm-0">
                                <a wire:click="removeFromCartList({{ $product->id }})" class="link link-dark-primary link-normal" ><i class="uil uil-multiply size-22"></i></a> 
                            </div>
                            <div class="w-100 d-block d-sm-none"></div>
                            <div class="col-auto align-self-center shop-cart-img">
                                <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="animsition-link">
                                    @if($product->parent->file_name)
                                        <img class="border-4" src=" {{ asset('/storage/' . $product->parent->file_name) }}" alt="product" width="80" >
                                    @else
                                        <img  class="border-4"src="{{ asset('/img/ga/not0.png')}}" alt="{{ $product->parent->name }}" width="80">
                                    @endif
                                </a> 
                            </div>
                            <div class="w-100 d-block d-sm-none"></div>
                            <div class="col align-self-center mt-3 mt-sm-0">
                                <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="link link-dark-primary link-normal mb-1 animsition-link">
                                    {{ $product->parent->name }}
                                </a> 
                                <p class="mb-0 size-14">
                                    @lang('Color'): {{ $product->color_name }}
                                </p> 
                                <p class="mb-0 size-14">
                                    @lang('Size'): {{ $product->size_name }}
                                </p> 
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col align-self-center mt-4 mt-lg-0">
                                <h6 class="mb-0 color-secondary font-weight-700">
                                    ${{!is_null($product->price) || $product->price != 0 ? 
                                    $product->price : $product->parent->price }}
                                </h6> 
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col-auto align-self-center mt-4 mt-lg-0">
                                <livewire:frontend.cart.cart-list-update-form :item="$product" :key="$product->id" :typeCart="'products'"/>
                            </div>
                            <div class="w-100 d-block d-lg-none"></div>
                            <div class="col align-self-center text-lg-right mt-4 mt-lg-0">
                                <h6 class="mb-0 font-weight-700">
                                    {{-- $420.00 --}}
                                    @json($product->amount)
                                </h6> 
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        {{-- <div class="col-12 text-lg-right mt-4">
            <h5 class="mb-3">
                Cart totals
            </h5> 
        </div>
        <div class="col-lg offset-lg-8 offset-xl-9">
            <div class="row">
                <div class="col">
                    <h6 class="mb-2 color-secondary">
                        Subtotal:
                    </h6> 
                </div>
                <div class="col-auto">
                    <h6 class="mb-2 color-secondary">
                        $820.00
                    </h6> 
                </div>
            </div>
        </div>
        <div class="col-lg offset-lg-8 offset-xl-9">
            <div class="row">
                <div class="col">
                    <h5 class="mb-0">
                        Total:
                    </h5> 
                </div>
                <div class="col-auto">
                    <h5 class="mb-0">
                        $820.00
                    </h5> 
                </div>
            </div>
        </div> --}}
        <div class="col-12 text-lg-right mt-4">
            <a href="{{ route('frontend.cart.index') }}" role="button" class="btn btn-dark-primary animsition-link">
                @lang('Proceed to checkout')
                <i class="uil uil-arrow-right size-20 ml-2"></i>
            </a> 
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <h2 class="display-8 text-center mb-4">
                @lang('¡Your cart order is empty!')
            </h2>

            <div class="col-12 text-center pt-5"> 
                <p id="contact-message-feedback"></p> 
                <a href="{{ route('frontend.shop.index') }}" class="btn btn-dark-primary">@lang('Go to products')<i class="uil uil-arrow-right size-22 ml-3"></i></a>         
            </div> 
        </div>
    </div>
@endif
