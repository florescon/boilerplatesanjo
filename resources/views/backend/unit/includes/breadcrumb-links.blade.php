@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.unit.deleted')" :text="__('Deleted units')" />
@endif
