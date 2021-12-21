@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.service.deleted')" :text="__('Deleted services')" />
@endif