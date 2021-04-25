@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.product.deleted')" :text="__('Deleted products')" />
@endif
