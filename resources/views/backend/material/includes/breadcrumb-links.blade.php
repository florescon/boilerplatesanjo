@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.material.deleted')" :text="__('Deleted feedstocks')" />
@endif
