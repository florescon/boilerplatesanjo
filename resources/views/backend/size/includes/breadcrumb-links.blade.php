@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.size.deleted')" :text="__('Deleted sizes')" />
@endif
