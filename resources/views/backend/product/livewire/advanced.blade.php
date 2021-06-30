<x-backend.card>

	<x-slot name="header">
        @lang('Information product')
 	</x-slot>

    <x-slot name="headerActions">

	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" />

        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

    <x-slot name="body">
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            Caracteristicas y descripcion
          </div>
          <form wire:submit.prevent="storedescription">
            <div class="card-body">

                    <x-input.rich-text2 wire:model.lazy="description" id="description" :initial-value="$description" />

                    {{-- {!! optional($model->advanced)->description !!} --}}

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>

        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            Informacion tecnica
          </div>
          <form wire:submit.prevent="storeinformation">
            <div class="card-body">

                    <x-input.rich-text wire:model.lazy="information" id="information" :initial-value="$information" />

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>

	</x-slot>

</x-backend.card>