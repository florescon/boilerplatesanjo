@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.order.deleted_work')" :text="__('Show deleted')" />
@endif
