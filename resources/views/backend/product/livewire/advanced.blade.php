<x-backend.card>

	<x-slot name="header">
        @lang('Product information')
 	</x-slot>

  <x-slot name="headerActions">
	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $product_id)" :text="__('Go to edit product')" />
        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

  <x-slot name="body">
    <div class="row ">
      <div class="col-12 col-md-4">
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            <h5>
              Informacion técnica
            </h5>
          </div>
          <form wire:submit.prevent="storeinformation">
            <div class="card-body">
              <x-input.rich-text wire:model.lazy="information" id="information" :initial-value="$model->information" />
            </div>
            <div class="card-footer text-center">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">
                  @lang('Save')
                </button>
                <div class="dropdown">
                  <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="#" wire:click="clearField({{ $product_id}}, '{{ 'information' }}' )">
                      @lang('Clear')
                    </a>
                  </div>
                </div>
              </div>
            </div>            
          </form>
        </div>
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            <h5>
              @lang('Standards')
            </h5>
          </div>
          <form wire:submit.prevent="storestandards">
            <div class="card-body">
              <x-input.rich-text wire:model.lazy="standards" id="standards" :initial-value="$standards" />
            </div>
            <div class="card-footer text-center">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">
                  @lang('Save')
                </button>
                <div class="dropdown">
                  <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="#" wire:click="clearField({{ $product_id}}, '{{ 'standards' }}' )">
                      @lang('Clear')
                    </a>
                  </div>
                </div>
              </div>
            </div>            
          </form>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            <h5>
              @lang('Dimensions')
            </h5>
          </div>
          <form wire:submit.prevent="storedimensions">
            <div class="card-body">
              <x-input.rich-text wire:model.lazy="dimensions" id="dimensions" :initial-value="$dimensions" />
            </div>
            <div class="card-footer text-center">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">
                  @lang('Save')
                </button>
                <div class="dropdown">
                  <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="#" wire:click="clearField({{ $product_id}}, '{{ 'dimensions' }}' )">
                      @lang('Clear')
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            <h5>
              @lang('Extra information')
            </h5>
          </div>
          <form wire:submit.prevent="storeextra">
            <div class="card-body">
              <x-input.rich-text wire:model.lazy="extra" id="extra" :initial-value="$extra" />
            </div>
            <div class="card-footer text-center">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">
                  @lang('Save')
                </button>
                <div class="dropdown">
                  <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="#" wire:click="clearField({{ $product_id}}, '{{ 'extra' }}' )">
                      @lang('Clear')
                    </a>
                  </div>
                </div>
              </div>
            </div>            
          </form>
        </div>
      </div>

      <div class="col-12 col-md-4 {{ !$product->advanced ? 'row justify-content-center' : '' }}">
        <div class="card card-product_not_hover">
          <div class="card-header text-primary">
            <h5>
              Características y descripción
            </h5>
          </div>
          <form wire:submit.prevent="storedescription">
            <div class="card-body">
              <x-input.rich-text wire:model.defer="description" id="description" :initial-value="$description" />
            </div>
            <div class="card-footer text-center">
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button class="btn btn-sm btn-primary" style="width: 150px"  type="submit">
                    @lang('Save')
                  </button>
                  <div class="dropdown">
                    <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="#" wire:click="clearField({{ $product_id}}, '{{ 'description' }}' )">
                        @lang('Clear')
                      </a>
                    </div>
                  </div>
                </div>
            </div>            
          </form>
        </div>
        <div class="card card-product_not_hover" style="{{ !$product->advanced ? 'width: 17.5rem;' : '' }}">
          @if(!$product->advanced)
            <img class="card-img-top" src="{{ asset('/img/ga/no-data-2.jpg')}}" alt="Card image cap">
          @else
          <br>
            <div class="row card-body justify-content-center text-center mt-5 mb-5">
                <a href="{{ route('frontend.shop.datasheet', $product->slug) }}" target="_blank" class="btn btn-sm btn-warning text-white mt-5 mb-5" style="width: 150px">
                  @lang('Show and print')
                </a>
            </div>
          @endif
        </div>
      </div>
    </div>
	</x-slot>

  @if($product->advanced)
    <x-slot name="footer">
      <div class="btn-group" role="group" aria-label="Basic example">
        <div class="dropdown">
          <em>
            @lang('Clear all')
          </em>
          <a class="btn btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
            <a class="dropdown-item text-danger" href="#" wire:click="clearAll">
              <em>@lang('Clear all descriptions')</em>
            </a>
          </div>
        </div>
      </div>
    </x-slot>
  @endif
</x-backend.card>