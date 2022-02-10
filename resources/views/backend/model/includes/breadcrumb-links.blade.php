@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.access.model_product.deleted'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.model.deleted')" :text="__('Deleted models')" />
@endif
