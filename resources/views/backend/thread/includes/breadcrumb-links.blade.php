@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.store.list'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.thread.deleted')" :text="__('Deleted Threads')" />
@endif
