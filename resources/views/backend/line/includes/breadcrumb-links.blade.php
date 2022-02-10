@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.line.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.line.deleted')" :text="__('Deleted lines')" />
@endif
