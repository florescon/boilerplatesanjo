@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.cloth.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.cloth.deleted')" :text="__('Deleted cloths')" />
@endif
