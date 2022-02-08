@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.brand.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.brand.deleted')" :text="__('Deleted brands')" />
@endif
