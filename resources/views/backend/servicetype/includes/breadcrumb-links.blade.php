@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.servicetype.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.servicetype.deleted')" :text="__('Deleted services type')" />
@endif
