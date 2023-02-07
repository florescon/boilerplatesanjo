<x-backend.card>
  <x-slot name="header">
        @lang('Delete attributes')
  </x-slot>

  <x-slot name="headerActions">
        <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $productId)" :text="__('Go to edit product')" />
  </x-slot>

  <x-slot name="body">
      <div class="alert alert-danger text-center" role="alert">
        Estás a punto de eliminar un atributo del producto <a href="#">{{ $product->name }}</a>. Al final que selecciones, te mostrará el botón de eliminar.
        <br>
        Esta acción es irrevocable 
      </div>
      <div class="row">
        @if($product->children->count())
          <div class="col-12 col-md-6">
              <div class="card card-pricing popular shadow">
                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-danger text-white shadow-sm"><h4>@lang('Colors')</h4></span>
                <div class="card-body">
                  @if($attributes->children->unique('color_id')->count() > 1)
                    <div class="list-group list-group-accent">
                      @foreach($attributes->children->unique('color_id')->sortBy('color.name') as $children)
                        <div class="list-group-item {{ in_array($children->color_id, $filters) ? ' list-group-item-accent-danger list-group-item-danger' : 'list-group-item-accent-light list-group-item-light' }}" wire:click="$emit('filterByColor', {{ $children->color_id }})"
                        style="cursor:pointer"><strong>{{ optional($children->color)->name }}</strong> <em class="text-right">{{ optional($children->color)->short_name }}</em></div>
                      @endforeach
                    </div>
                  @else
                    <p class="text-center">@lang('You only have one color, you can\'t remove')</p>
                  @endif
                </div>
              </div>
          </div>
          <div class="col-12 col-md-6">
              <div class="card card-pricing popular shadow">
                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-danger text-white shadow-sm"><h4>@lang('Sizes')</h4></span>
                <div class="card-body">
                  @if($attributes->children->unique('size_id')->count() > 1)
                    <div class="list-group list-group-accent">
                      @foreach($attributes->children->unique('size_id')->sortBy('size.sort') as $children)
                        <div class="list-group-item {{ in_array($children->size_id, $filtersz) ? ' list-group-item-accent-danger list-group-item-danger' : 'list-group-item-accent-light list-group-item-light' }}" wire:click="$emit('filterBySize', {{ $children->size_id }})"
                        style="cursor:pointer"><strong>{{ optional($children->size)->name }}</strong> <em class="text-right">{{ optional($children->size)->short_name }}</em></div>
                      @endforeach
                    </div>
                  @else
                    <p class="text-center">@lang('You only have one size, you can\'t remove')</p>
                  @endif
                </div>
              </div>
          </div>
        @endif

        @if($filters || $filtersz)
          <div class="col-12 col-md-12">
            <div class="jumbotron text-center">
              <div class="row">
                <div class="col-sm">
                  <img src="{{ asset('/img/flame.gif')}}" alt="Porto Logo">
                </div>
                <div class="col-sm">
                  <h1 class="display-4">{{ $attributeColor->name ?? $attributeSize->name }}</h1>
                  <p class="lead">{{ optional($attributeColor)->short_name ?? optional($attributeSize)->short_name }}</p>
                </div>
                <div class="col-sm">
                  <img src="{{ asset('/img/flame.gif')}}" alt="Porto Logo">
                </div>
              </div>
              <hr class="my-4">
              <p>@lang('Irreversible action, all combinations created in this product will be eliminated')</p>
              @if($filters)
                <a class="btn btn-danger btn-lg" href="#" wire:click="deleteColor({{ $filters[0] }})" role="button">@lang('Delete product color')</a>
              @else
                <a class="btn btn-danger btn-lg" href="#" wire:click="deleteSize({{ $filtersz[0] }})" role="button">@lang('Delete size product')</a>
              @endif
            </div>
          </div>
        @endif

      </div>

  </x-slot>

</x-backend.card>