@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.size.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.size.deleted')" :text="__('Deleted sizes')" />
@endif
