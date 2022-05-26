<x-backend.card>
	<x-slot name="header">
        @lang('Prices and codes')
 	</x-slot>

    <x-slot name="headerActions">
	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $product_id)" :text="__('Go to edit product')" />
        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

    <x-slot name="body">
        <section class="ftco-section">
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row d-flex justify-content-center">
                            <div class="col-sm-6 col-lg-10 grid-margin stretch-card">

                              <div class="card">
                                <div class=" card-up aqua-gradient card-header content-center">
                                    <h2 class="text-white my-3">
                                        {{ $product_name }}
                                    </h2>
                                </div>
                                <div class="card-body row text-center">
                                  <div class="col">
                                    <div class="text-value-xl mb-2">{{ $product_code }}</div>

                                    <p class="custom-control custom-switch m-0 text-uppercase text-muted">
                                        <input class="custom-control-input" id="customCodes" type="checkbox" wire:model="customCodes">
                                        <label class="custom-control-label font-italic" for="customCodes">@lang('Codes')</label>
                                    </p>


                                    @if($customCodes == true)
                                        <h3 class="h5 my-4 text-center">
                                            <x-utils.form-button
                                                :action="route('admin.product.create-codes', $product_id)"
                                                name="confirm-item"
                                                button-class="btn btn-outline-primary"
                                                >
                                                @lang('Create codes automatically')
                                            </x-utils.form-button>
                                        </h3>
                                    @endif

                                    </div>
                                    <div class="c-vr"></div>
                                    <div class="col md-6">
                                        <div class="text-value-xl mb-2">${{ $product_price }}</div>
                                        <p class="custom-control custom-switch m-0 text-uppercase text-muted">
                                            <input class="custom-control-input" id="customPrices" type="checkbox" wire:model="customPrices">
                                            <label class="custom-control-label font-italic" for="customPrices">@lang('Prices')</label>
                                        </p>
                                        @if($customPrices)
                                            <div class="card mt-4 border-0">

                                                <div class="col-md-12 mb-3">
                                                    <input type="number" min="1" step="any" name="price" wire:model="price" class="form-control @error('price') is-invalid  @enderror" placeholder="{{ __('Price') }}" value="{{ old('price') }}" maxlength="100" required/>
                                                </div>

                                                <div class="col-md-12 text-center mb-3">
                                                    <div class="custom-control custom-switch">
                                                      <input type="checkbox" class="custom-control-input" id="switchIVA" wire:click="$toggle('switchIVA')">
                                                      <label class="custom-control-label" for="switchIVA">Precio de proveedor no incluye IVA, incluirlo</label>
                                                    </div>
                                                </div>

                                                <div class="input-group">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">${{ $product_price }}</span>
                                                  </div>
                                                  <input type="text" class="form-control" wire:model.lazy="retail_price" aria-label="Text input with segmented dropdown button" placeholder="@lang('Retail price')">
                                                  <div class="input-group-append">
                                                    <button type="button" wire:click="saveRetail" class="btn btn-outline-primary">@lang('Save')</button>
                                                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <span class="sr-only">@lang('Custom')</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                      <a class="dropdown-item" href="#" wire:click="saveRetail">@lang('Only save')</a>
                                                      <div role="separator" class="dropdown-divider"></div>
                                                      <a class="dropdown-item" href="#" wire:click="saveRetail(true)">@lang('Save and clear all retail price variants')</a>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            @error('retail_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                            <div class="card mt-4 border-0">
                                                <div class="input-group">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">${{ $product_average_wholesale_price }}</span>
                                                  </div>
                                                  <input type="text" class="form-control" wire:model.lazy="average_wholesale_price" aria-label="Text input with segmented dropdown button" placeholder="@lang('Average wholesale price')">
                                                  <div class="input-group-append">
                                                    <button type="button" wire:click="saveAverageWholesale" class="btn btn-outline-primary">@lang('Save')</button>
                                                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <span class="sr-only">@lang('Custom')</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                      <a class="dropdown-item" href="#" wire:click="saveAverageWholesale">@lang('Only save')</a>
                                                      <div role="separator" class="dropdown-divider"></div>
                                                      <a class="dropdown-item" wire:click="saveAverageWholesale(true)" href="#">@lang('Save and clear all average wholesale price variants')</a>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            @error('average_wholesale_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                            <div class="card mt-4 border-0">
                                                <div class="input-group">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">${{ $product_wholesale_price }}</span>
                                                  </div>
                                                  <input type="text" class="form-control" wire:model.lazy="wholesale_price" aria-label="Text input with segmented dropdown button" placeholder="@lang('Wholesale price')">
                                                  <div class="input-group-append">
                                                    <button type="button" wire:click="saveWholesale" class="btn btn-outline-primary">@lang('Save')</button>
                                                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <span class="sr-only">@lang('Custom')</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                      <a class="dropdown-item" href="#" wire:click="saveWholesale">@lang('Only save')</a>
                                                      <div role="separator" class="dropdown-divider"></div>
                                                      <a class="dropdown-item" wire:click="saveWholesale(true)" href="#">@lang('Save and clear all wholesale price variants')</a>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            @error('wholesale_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

                                            <div class="card mt-4 border-0">
                                                <div class="input-group">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">${{ $product_special_price }}</span>
                                                  </div>
                                                  <input type="text" class="form-control" wire:model.lazy="special_price" aria-label="Text input with segmented dropdown button" placeholder="@lang('Special price')">
                                                  <div class="input-group-append">
                                                    <button type="button" wire:click="saveSpecial" class="btn btn-outline-primary">@lang('Save')</button>
                                                    <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <span class="sr-only">@lang('Custom')</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                      <a class="dropdown-item" href="#" wire:click="saveSpecial">@lang('Only save')</a>
                                                      <div role="separator" class="dropdown-divider"></div>
                                                      <a class="dropdown-item" wire:click="saveSpecial(true)" href="#">@lang('Save and clear all special price variants')</a>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            @error('special_price') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

                                        @endif
                                    </div>
                                </div>
                               </div>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <div class="table-responsive">
                            <table class="table myaccordion table-hover" id="accordion">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Product name')</th>
                                        <th class="table-secondary">@lang('Code')</th>
                                        <th class="table-secondary">@lang('Retail price')</th>
                                        @if($customPrices == true)
                                            <th class="table-secondary col-2"></th>
                                        @endif
                                        <th class="table-secondary">@lang('Average wholesale price')</th>
                                        @if($customPrices == true)
                                            <th class="table-secondary col-2"></th>
                                        @endif
                                        <th class="table-secondary">@lang('Wholesale price')</th>
                                        @if($customPrices == true)
                                            <th class="table-secondary col-2"></th>
                                        @endif
                                    </tr>
                                </thead>

                                @error('productModel.*.price')
                                    <span class="error" style="color: red;">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror

                                @error('productModel.*.average_wholesale_price')
                                    <span class="error" style="color: red;">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror

                                @error('productModel.*.wholesale_price')
                                    <span class="error" style="color: red;">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror

                                <tbody>
                                    @foreach($productModel as $key => $subproduct)
                                        <tr>
                                          <th scope="row">{{ $subproduct->id }}</th>
                                          <td>
                                              <span class="mb-0 text-sm mr-2">{!! '<strong>' .$subproduct->parent->name.' </strong> ('.optional($subproduct->color)->name.'  '.optional($subproduct->size)->name.') ' !!}
                                              </span>
                                              {!! optional($subproduct->color)->undefined_icon_coding !!}
                                              {!! optional($subproduct->size)->undefined_icon_coding !!}
                                          </td>
                                          <td class="table-danger">{!! $subproduct->code_subproduct !!}</td>
                                          <td class="table-info">${!! number_format((float)$subproduct->price_subproduct, 2) !!}</td>

                                          @if($customPrices == true)
                                              <td class="table-info"> 
                                                <input type="number" 
                                                    wire:model.lazy="productModel.{{ $key }}.price"
                                                    wire:keydown.enter="save" 
                                                    class="form-control" placeholder="{{ number_format((float)$subproduct->price_subproduct, 2) }}"
                                                    step="any" 
                                                >
                                              </td>
                                          @endif

                                          <td class="table-info">${!! number_format((float)$subproduct->price_average_wholesale_subproduct, 2) !!}</td>
                                          @if($customPrices == true)
                                              <td class="table-info"> 
                                                <input type="number" 
                                                    wire:model.lazy="productModel.{{ $key }}.average_wholesale_price"
                                                    wire:keydown.enter="save" 
                                                    class="form-control" placeholder="{!! number_format((float)$subproduct->price_average_wholesale_subproduct, 2) !!}"
                                                    step="any" 
                                                >
                                              </td>
                                          @endif

                                          <td class="table-info">${!! number_format((float)$subproduct->price_wholesale_subproduct, 2) !!}</td>
                                          @if($customPrices == true)
                                              <td class="table-info"> 
                                                <input type="number" 
                                                    wire:model.lazy="productModel.{{ $key }}.wholesale_price"
                                                    wire:keydown.enter="save" 
                                                    class="form-control" placeholder="{!! number_format((float)$subproduct->price_wholesale_subproduct, 2) !!}"
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