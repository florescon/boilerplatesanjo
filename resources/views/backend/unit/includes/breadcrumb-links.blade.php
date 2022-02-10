@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.unit.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.unit.deleted')" :text="__('Deleted units')" />
@endif
