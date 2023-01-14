@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.vendor.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.vendor.deleted')" :text="__('Deleted vendors')" />
@endif
