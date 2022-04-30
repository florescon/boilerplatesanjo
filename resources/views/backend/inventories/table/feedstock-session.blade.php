<x-backend.card>
	<x-slot name="header">
        @lang('Make feedstock inventory')
 	</x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.inventory.index')" :text="__('Cancel')" />
 	</x-slot>
    <x-slot name="body">

        <div class="row" wire:ignore>
            <div class="col-md-12" style="text-align: center;margin-bottom: 20px;">
                <div id="reader" style="display: inline-block;"></div>
                <div class="empty"></div>
                <div id="scanned-result"></div>
            </div>
        </div>

	</x-slot>
</x-backend.card>