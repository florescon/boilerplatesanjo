@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.family.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.family.deleted')" :text="__('Deleted families')" />
@endif
