@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.brand.deleted')" :text="__('Deleted brands')" />
@endif
