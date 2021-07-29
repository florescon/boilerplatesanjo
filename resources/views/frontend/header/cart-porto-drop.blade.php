
<div class="dropdown-menu">
    <div class="dropdownmenu-wrapper">
     
    @if(count($cart))

        <div class="dropdown-cart-header">
            <span>{{ $cartTotal.' '.__('Products') }} </span>
            
            <a href="{{ route('frontend.cart.index') }}" class="float-right">@lang('View cart')</a>
        </div><!-- End .dropdown-cart-header -->
        
        <div class="dropdown-cart-header">
            <span>{{ $cartTotalOrder.' '.__('Products order') }} </span>
        </div><!-- End .dropdown-cart-header -->

        <div class="dropdown-cart-products">
            @foreach($cart as $product)
            <div class="product">
                <div class="product-details">
                    <h4 class="product-title">
                        <a href="{{ route('frontend.shop.show', $product->parent->slug) }}">
                            {!! $product->full_name !!}
                        </a>
                    </h4>
                    <span class="cart-product-info">
                        <span class="cart-product-qty">@lang('Quantity'):</span>
                        <em style="color:blue;">{{ $product->amount }}</em>
                    </span>
                </div><!-- End .product-details -->
                    
                <figure class="product-image-container2">
                    <a href="{{ route('frontend.shop.show', $product->parent->slug) }}" class="product-image">
                    @if($product->parent->file_name)
                        <img src=" {{ asset('/storage/' . $product->parent->file_name) }}" alt="product" width="80" >
                    @else
                        <img src="{{ asset('/porto/assets/images/not0.png')}}" alt="{{ $product->parent->name }}">
                    @endif
                    </a>
                    <a href="#" class="btn-remove icon-cancel" title="Remove Product"></a>
                </figure>
            </div><!-- End .product -->
            @endforeach
        </div><!-- End .cart-product -->
        
        <div class="dropdown-cart-total">
            <span>Total</span>
            
            <span class="cart-total-price float-right">$134.00</span>
        </div><!-- End .dropdown-cart-total -->
        
        <div class="dropdown-cart-action">
            <a href="{{ route('frontend.cart.index') }}" class="btn btn-dark btn-block">@lang('Go to cart')</a>
        </div><!-- End .dropdown-cart-total -->

    @else
        <p class="empty">You have no items.</p>
    @endif
    </div><!-- End .dropdownmenu-wrapper -->
</div><!-- End .dropdown-menu -->

