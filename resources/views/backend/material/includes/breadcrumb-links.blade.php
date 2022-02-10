@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.material.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.material.deleted')" :text="__('Deleted feedstocks')" />
@endif
