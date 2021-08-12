<x-backend.card>

	<x-slot name="header">
        @lang('Prices product')
 	</x-slot>

    <x-slot name="headerActions">

	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $product_id)" :text="__('Go to edit product')" />

        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

    <x-slot name="body">
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center mb-4">
                        <h2 class="heading-section">{{ $product_name }}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-wrap">
                        <h3 class="h5 mb-4 text-center">
                            <div class="btn-group" role="group" aria-label="First group">
                                <kbd><kbd>${{ $product_code }}</kbd></kbd>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic example">

                            <p class="custom-control custom-switch m-0">
                                <input class="custom-control-input" id="customCodes" type="checkbox" wire:model="customCodes">
                                <label class="custom-control-label font-italic" for="customCodes">@lang('Codes')</label>
                            </p>

                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <p class="custom-control custom-switch m-0">
                                <input class="custom-control-input" id="customPrices" type="checkbox" wire:model="customPrices">
                                <label class="custom-control-label font-italic" for="customPrices">@lang('Prices')</label>
                            </p>

                            </div>
                            <div class="btn-group" role="group" aria-label="Third group">
                                <kbd><kbd>${{ $product_price }}</kbd></kbd>
                            </div>
                        </h3>


                        @if($customCodes == true)
                        <h3 class="h5 mb-4 text-center">

                                <x-utils.form-button
                                    :action="route('admin.product.create-codes', $product_id)"
                                    name="confirm-item"
                                    button-class="form-control"
                                >
                                    @lang('Create codes automatically')
                                </x-utils.form-button>

                        </h3>
                        @endif
                            <div class="table-responsive">
                            <table class="table myaccordion table-hover" id="accordion">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Product name')</th>
                                        <th class="table-secondary">@lang('Code')</th>
                                        <th class="table-secondary">@lang('Price')</th>
                                        @if($customPrices == true)
                                        <th class="table-secondary col-2"></th>
                                        @endif
                                    </tr>
                                </thead>

                                          @error('productModel.*.price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

                                <tbody>

                                    @foreach($productModel as $key => $subproduct)
                                        <tr>
                                          <th scope="row">{{ $subproduct->id }}</th>
                                          <td>
                                              <span class="mb-0 text-sm">{!! '<strong>' .$subproduct->parent->name.' </strong> ('.optional($subproduct->color)->name.'  '.optional($subproduct->size)->name.') ' !!}</span>
                                          </td>
                                          <td class="table-danger">{!! $subproduct->code_subproduct !!}</td>
                                          <td class="table-info">${!! $subproduct->price_subproduct !!}</td>

                                          @if($customPrices == true)
                                          <td class="table-info"> 
                                            <input type="number" 
                                                wire:model="productModel.{{ $key }}.price"
                                                wire:keydown.enter="save" 
                                                class="form-control" placeholder="{{ $product_price }}"
                                                step="any" 
                                            >
                                          </td>
                                          @endif
                                        </tr>
                                    @endforeach


                                    {{-- @json($productModel) --}}

{{--                                     @foreach($model->children as $subproduct)
                                        <tr>
                                          <th scope="row">{{ $subproduct->id }}</th>
                                          <td>
                                              <span class="mb-0 text-sm">{!! '<strong>' .$subproduct->parent->name.' </strong> ('.optional($subproduct->color)->name.'  '.optional($subproduct->size)->name.') ' !!}</span>
                                          </td>
                                          <td class="table-danger">{!! $subproduct->code_subproduct !!}</td>
                                          <td class="table-danger"> 
                                            <input type="text" 
                                                wire:model="productModel.{{ $subproduct->id }}.code"
                                                wire:keydown.enter="updateCode" 
                                                class="form-control" placeholder="@lang('Update')"
                                            >
                                          </td>
                                          <td class="table-info">${!! $subproduct->price_subproduct !!}</td>
                                        </tr>
                                    @endforeach
 --}}                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</x-slot>

</x-backend.card>