@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.thread.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.thread.deleted')" :text="__('Deleted threads')" />
@endif
