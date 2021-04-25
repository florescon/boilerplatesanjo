<li class="c-header-nav-item px-3">
    <a class="c-header-nav-link" href="{{ route('admin.cart.index') }}">
        @lang('Cart order') {!! $cartTotal > 0 ? '<p class="text-info">('. $cartTotal.')</p>' : '' !!}
    </a>
</li>
