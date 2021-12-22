@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.model.deleted')" :text="__('Deleted models')" />
@endif
