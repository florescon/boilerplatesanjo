@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="searchProduct">
  <x-slot name="title">
    @lang('Search product')
  </x-slot>

  <x-slot name="content">
    <div class=" row mb-4 justify-content-md-center">
      <div class="col-8">
        <div class="input-group">
          <input 
            wire:model="query" 
            type="text" 
            class="input-search"
            placeholder="{{ __('Search') }}..."
            wire:keydown.escape="reset_search"
            {{-- wire:keydown.tab="reset_search" --}}
            wire:keydown.ArrowUp="decrementHighlight"
            wire:keydown.ArrowDown="incrementHighlight"
            wire:keydown.enter="dropdown"
           />
            <span class="border-input-search"></span>


        </div>
        <div wire:loading wire:target="query">@lang('Searching')...</div>
      </div>


      @if(!empty($query))
        <div class="input-group-append">
          <button type="button" wire:click="reset_search" class="close" aria-label="Close">
            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
          </button>

        </div>
      @endif

    </div>

      <div class="row">
        <div class="col-12 text-center">

          <div class="form mb-3">
            <form>
              <fieldset class="form__options">
                <p class="form__answer"> 
                  <input type="radio" name="match" id="match_1" wire:model="match" value="products_sale" checked> 
                  <label for="match_1">
                    <img class="" src="{{ asset('img/sale.png') }}" height="55" >
                    @lang('Sale')
                  </label> 
                </p>
                
                <p class="form__answer"> 
                  <input type="radio" name="match" wire:model="match" id="match_2" value="products"> 
                  <label for="match_2">
                  <img class="" src="{{ asset('img/order.png') }}" height="55" >
                    @lang('Order')
                  </label> 
                </p>
              </fieldset>
            </form>
          </div>

        </div>
      </div>


    <div class="mt-3">
      @if(!empty($query))
        <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="reset_search"></div>

        <div class="list-group absolute shadow-sm">
          @if(!empty($products))

              {{-- @json($products) --}}
              
              @foreach($products as $i => $product)
                <a href="#" wire:click="selectProduct({{ $product['id'] }})" class="list-group-item list-group-item-action {{ $highlightIndex === $i ? '' : '' }}">
                  {!!  $product['name'].' '.
                      (!is_null($product['parent']) ? $product['parent']['name']  :  '').' '. 
                      (!is_null($product['code']) ? '<p class="font-weight-bold" style="display:inline">'.$product['code'].'</p> '  :  '').'&nbsp;&nbsp;&nbsp;&nbsp;  '. 
                      (!is_null($product['color']) ? '<em>'.$product['color']['name'].'</em>'  :  '').' '. 
                      (!is_null($product['size']) ? '<em>'.$product['size']['name'].'</em>'  :  '').'  '.
                      (is_null($product['parent_id']) ? " <span class='badge badge-primary'>".__('Main').'</span>': '').' '
.                      (!$product['type'] === true ? " <span class='badge badge-info' style='background-color: #85144b'>".__('Service').'</span>' : '')
 
                  !!}
                </a>
              @endforeach
          @else
            <div class="list-group-item">@lang('No results!')</div>
          @endif
        </div>

        {{-- <div class="col-md-12 text-center">
          <input class="btn btn-info mt-3" type="reset" value="{{ __('Clear search') }}" wire:click="reset_search">
        </div> --}}

      @endif


      @if($selectedProduct)
      <div class="row">
        <div class="col-12 text-center mt-4">
          {!!  $full_name ?? '' !!}
        </div>
      </div>


        @if($selectedProduct->children->count())
          <div class="row mt-3">
            <div class="col-md-6 text-center">
                  @foreach($selectedProduct->children->unique('size_id')->sortBy('size.sort') as $children)  
                  <span class="badge text-white {{ in_array($children->size_id, $filtersz) ? 'bg-danger' : 'bg-dark' }}" 
                              wire:click="$emit('filterBySize', {{ $children->size_id }})"
                      style="cursor:pointer"
                  >{{ optional($children->size)->name }}</span>
                @endforeach
            </div>        

            <div class="col-md-6 text-center border-left">
              @foreach($selectedProduct->children->unique('color_id')->sortBy('color.name') as $children)  
                <span class="badge text-white {{ in_array($children->color_id, $filters) ? 'bg-danger' : 'bg-dark' }}" 
                            wire:click="$emit('filterByColor', {{ $children->color_id }})"
                    style="cursor:pointer"
                >{{ optional($children->color)->name }}</span>
              @endforeach
            </div>
          </div>

          @if($model != null && $model->children->count())
            <div class="row justify-content-center align-content-center mt-3">
              <div class="col-md-12 mt-2">
                <ul class="list-group list-group-accent">
                @foreach($model->children as $children)
                  <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-accent-primary list-group-item-primary">
                    {!! $children->full_name !!}
                    <span class="badge badge-success badge-pill"  href="#" style="cursor:pointer" wire:click="selectProduct({{ $children->id }})">@lang('Add to cart')</span>
                    
                  </li>
                @endforeach
                </ul>
              </div>
            </div>
          @endif
        @else
          <div class="row">
            <div class="col-12 text-center mt-4">
              <img class="" src="{{ asset('img/noresult.gif') }}" height="150" >
              <p>@lang('No associated data')</p>
                <a href="{{ route('admin.product.edit', $selectedProduct->id) }}" class="badge badge-danger">@lang('Go to edit product')</a>
            </div>
          </div>
        @endif

      @endif

    </div>

  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#sizeselectmuliple').select2({
          placeholder: '@lang("Choose sizes")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          multiple: true,
          ajax: {
                url: '{{ route('admin.size.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });

          $('#sizeselectmuliple').on('change', function (e) {
            var data = $('#sizeselectmuliple').select2("val");
            @this.set('sizesmultiple_id', data);
          });


      });
    </script>
@endpush