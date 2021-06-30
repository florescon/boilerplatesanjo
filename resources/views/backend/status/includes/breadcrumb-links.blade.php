@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.status.deleted')" :text="__('Deleted statuses')" />
@endif
