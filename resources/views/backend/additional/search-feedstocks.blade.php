@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="searchProduct" width="modal-lg">
  <x-slot name="title">
    @lang('Add feedstock')
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

    @if(!empty($query))
    <div class="card-body">
        @if(!empty($products))
            <div class="card-columns">
              @foreach($products as $i => $product)
              <a href="#" wire:click="selectProduct({{ $product['id'] }})">
                <div class="card card-flyer card-product">
                  <div class="card-body"  style="transform: rotate(0);">
                    <h5 class="card-title text-dark text-center"><strong>{!! $product['name'] .'<br>'. (!is_null($product['color']) ? '<em>'.$product['color']['name'].'</em>'  :  '') !!}</strong></h5>
                    <h5 class="card-title text-muted text-center">{{ $product['part_number'] }}</h5>
                    <div class="text-center">
                        <h2 class="text-primary">
                          {{ $product['price'] ? '$'.$product['price'] : 'undefined price' }}
                        </h2>
                        <h3 class="text-dark">
                          {!! $product['stock'].' '.(!is_null($product['unit']) ? '<em>'.($product['unit']['abbreviation'] ?? $product['unit']['name']).'</em>'  :  '') !!}
                        </h3>

                    </div>
                  </div>

                </div>
              </a>
              @endforeach
            </div>
        @endif
    </div>
    @endif


    @if($selectedProduct)
      <div class="row justify-content-center">
          <div class="card shadow-lg" style="width: 60%;">
            <div class="card-body">
              <h5 class="card-title text-center">{!!  $full_name ?? '' !!}</h5>
              {{-- <p class="card-text">{{ $selectedProduct->description }}</p> --}}
              <h2 class="text-primary text-center">
                {{ $price }}
              </h2>
              <h3 class="text-center">{{ $stock }}</h3>
            
            <input class="form-control text-center opacity-placeholder mt-4" style="background-image: none; color:red" wire:model.defer="inputformat.{{ $selectedProduct->id }}" wire:keydown.enter="format" placeholder="{{ __('Quantity') }}" type="text" min="1" aria-label="Username" aria-describedby="basic-addon1" >


            @error('inputformat.'.$selectedProduct->id)
            <div class="text-center mt-4">
                <span class="error" style="color: red;">
                    <p>{{ $message }}</p>
                </span>
            </div>
            @enderror

            </div>
          </div>
      </div>
    @endif

  </x-slot>

  <x-slot name="footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>
