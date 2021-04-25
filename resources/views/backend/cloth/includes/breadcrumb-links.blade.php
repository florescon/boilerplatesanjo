@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.cloth.deleted')" :text="__('Deleted cloths')" />
@endif
