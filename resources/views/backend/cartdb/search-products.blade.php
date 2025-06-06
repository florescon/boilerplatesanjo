@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="searchProduct" width="modal-xl">
  <x-slot name="title">
    @lang('Add product') 
    {{-- - {{ __(ucfirst($type)) }} --}}
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
                <div class="card card-flyer {{ $product['type'] == false ? 'bg-dark text-white' : '' }} card-product">
                  @if($product['file_name'])
                    <img class="card-img-top" src="{{ asset('/storage/' . $product['file_name']) }}" alt="{{ $product['name'] }}">
                  @endif
                  <div class="card-body"  style="transform: rotate(0);">
                    <h5 class="card-title text-center"><strong>{{ $product['name'] }}</strong></h5>
                    <h5 class="card-title text-muted text-center">{{ $product['code'] }}</h5>
                    <div class="text-center">
                        <h2 class="text-primary">
                            ${{ priceIncludeIva($product['price']) ?? 0 }}
                        </h2>
                        <div class="small text-muted"> {{ $product['price'] ? '$'.$product['price'] : 'undefined price' }} </div>
                    </div>
                  </div>
                  @if($product['brand_id'])
                    <div class="container">
                      <div class="row justify-content-center text-center">
                        <div class="col-4 p-1 mb-2 bg-dark text-white text-center border rounded-lg">
                            <strong>
                                {{ $product['brand']['name'] }}
                            </strong>
                        </div>
                      </div>
                    </div>
                  @endif

                </div>
              </a>
              @endforeach
            </div>
        @endif
    </div>
    @endif

      @if($selectedProduct)
        <div class="row justify-content-center">
            <div class="card" style="width: 18rem;">
              <img class="card-img-top" src="{{ asset('/storage/' . $selectedProduct->file_name) }}" >
              <div class="card-body">
                <h5 class="card-title">{!!  $full_name ?? '' !!}</h5>
                <h5 class="card-title text-center">{{   $selectedProduct->code }}</h5>
                <p class="card-text">{{ $selectedProduct->description }}</p>
              </div>
            </div>
        </div>

        @if($selectedProduct->children->count())
          <div class="row mt-3">

            <div class="col-md-12 text-center border-left">
              @foreach($selectedProduct->children->unique('color_id')->sortBy('color.name') as $children)  
                <span class="badge text-white {{ in_array($children->color_id, $filterColor) ? 'bg-danger' : 'bg-dark' }}" 
                    wire:click="$emit('filterByColor', {{ $children->color_id }})"
                    style="cursor:pointer"
                >{{ optional($children->color)->name }}</span>
              @endforeach
            </div>
          </div>

          @if($selectedProduct != null && $selectedProduct->children->count())
            <div class="row justify-content-center align-content-center mt-3">
              <div class="col-md-12 mt-2">

                <table class="table">
                  <thead>
                    <tr>
                      @php($si = [])
                      <th></th>
                      @foreach($selectedProduct->children->unique('size_id')->sortBy('size.sort') as $children)
                        <th scope="col" class="text-center">{{ optional($children->size)->name }}</th>
                        @php($yas = $loop->count)
                      @php($si[] = array('id' => optional($children->size)->id, 'name' => optional($children->size)->name))             
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>

                    {{-- @json($si) --}}
                    <br>
                    {{-- {{ $yas.' '.'size' }} --}}


<!-- Mostrar el total -->
<tr wire:ignore>
    <td class="text-center" colspan="{{ $yas + 1 }}">
        <h3><strong>Total en vista: <span class="text-danger" id="total-sum">0</span></strong></h3>
    </td>
</tr>

@if($selectedProduct->children->count())
    @foreach($selectedProduct->children->unique('color_id')->sortBy('color.name') as $children)
        <tr>
            <th scope="row">
                {{ optional($children->color)->name }}

                @if($children->color->color)
                    <div class="box-color justify-content-md-center" style="background-color:{{ optional($children->color)->color }}; display: inline-block;"></div>
                @endif

                @if($children->color->secondary_color)
                    <div class="box-color justify-content-md-center" style="background-color:{{ optional($children->color)->secondary_color }}; display: inline-block;"></div>
                @endif
            </th>

            @for ($i = 0; $i < $yas ; $i++)
                <td scope="row">
                    @foreach($si as $sip)
                        @if($i == $loop->index)
                            <div class="input-group opacity-placeholder mb-3" style="{{ optional($children->color)->color ? 'border-bottom: 3px solid'.optional($children->color)->color.' !important;' : '' }}">
                                <input class="form-control text-center text-primary opacity-placeholder input-value" style="background-image: none; min-width: 18px; color:red" 
                                    wire:model.defer="inputformat.{{ optional($children->color)->id }}.{{ $sip['id'] }}" 
                                    wire:keydown.enter="format" 
                                    placeholder="{{ $sip['name'] }}" 
                                    type="text" 
                                    min="1" 
                                    aria-label="Username" 
                                    aria-describedby="basic-addon1" 
                                    id="input-{{ optional($children->color)->id }}-{{ $sip['id'] }}">
                            </div>

                            @error('inputformat.'. optional($children->color)->id .'.'.$sip['id'])
                                <span class="error" style="color: red;">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror
                        @endif
                    @endforeach
                </td>
            @endfor
        </tr>
    @endforeach
@endif

                  </tbody>
                </table>

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

  </x-slot>

  <x-slot name="footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
    <button type="button" wire:click="format" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>

@push('after-scripts')
<!-- Script para sumar los valores de los inputs -->
<script>
    document.addEventListener('livewire:load', function () {
        // Escuchar el evento emitido desde Livewire
        Livewire.on('triggerDOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.input-value');
        const totalSumElement = document.getElementById('total-sum');

        function calculateTotal() {
            let total = 0;
            inputs.forEach(input => {
                let value = parseFloat(input.value);
                if (!isNaN(value)) {
                    total += value;
                }
            });
            totalSumElement.textContent = total;
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        // Inicializa la suma al cargar la página
        calculateTotal();
    });
  });
</script>

@endpush