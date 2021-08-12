<x-backend.card>

	<x-slot name="header">
        @lang('Product information')
 	</x-slot>

    <x-slot name="headerActions">

	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" />

        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

    <x-slot name="body">
    <div class="row ">

      <div class="col-12 col-md-4">
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
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            @lang('Standards')
          </div>
          <form wire:submit.prevent="storestandards">
            <div class="card-body">

                    <x-input.rich-text wire:model.lazy="standards" id="standards" :initial-value="$standards" />

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>
      </div>


      <div class="col-12 col-md-4">

        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            @lang('Dimensions')
          </div>
          <form wire:submit.prevent="storedimensions">
            <div class="card-body">

                    <x-input.rich-text wire:model.lazy="dimensions" id="dimensions" :initial-value="$dimensions" />

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>

        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            @lang('Extra information')
          </div>
          <form wire:submit.prevent="storeextra">
            <div class="card-body">

                    <x-input.rich-text wire:model.lazy="extra" id="extra" :initial-value="$extra" />

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>

      </div>

      <div class="col-12 col-md-4">
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            Caracteristicas y descripcion
          </div>
          <form wire:submit.prevent="storedescription">
            <div class="card-body">

                    <x-input.rich-text wire:model.defer="description" id="description" :initial-value="$description" />

            </div>
            <div class="card-footer text-center">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">@lang('Save')</button>
            </div>            
          </form>
        </div>
      </div>


    </div>
	</x-slot>

</x-backend.card>