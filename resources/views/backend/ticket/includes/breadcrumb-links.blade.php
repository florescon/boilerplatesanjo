@if ($logged_in_user->hasAllAccess())
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.ticket.deleted')" :text="__('Deleted tickets')" />
@endif