{{-- <div class="text-danger" wire:offline >
    @lang('You are not currently connected to the internet.')    
</div> --}}


<li class="c-header-nav-item px-3">
    <a class="c-header-nav-link" href="{{ route('admin.cart.index') }}">
        @lang('Cart') {!! $cartTotal > 0 ? '<p class="text-primary">('. $cartTotal.')</p>' : '' !!}
        {!! $cartTotalSale > 0 ? '<p class="text-success">('. $cartTotalSale.')</p>' : '' !!}
    </a>
</li>
