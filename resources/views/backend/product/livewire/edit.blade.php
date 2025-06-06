<x-backend.card>
	<x-slot name="header">
		<strong class="no-print">
    	    @lang('Update product') {{ $nameStock }}<i class="cil-fire"></i>
	    </strong>
 	</x-slot>

    <x-slot name="headerActions">

    	@if( $logged_in_user->can('admin.access.product.consumption') || $logged_in_user->hasAllAccess())
        	<x-utils.link class="card-header-action btn btn-warning text-white no-print" :href="route('admin.product.consumption', $model->id)" :text="__('Go to consumption')" />
       	@endif

       	@if(!$nameStock)
	        <x-utils.link class="card-header-action no-print" :href="url()->previous()" :text="__('Back')" />
	    @else
	        <x-utils.link class="card-header-action no-print" :href="url()->previous()" :text="__('Back')" />
	    @endif
 	</x-slot>

    <x-slot name="body">

    	@if(!$model->status)
			<div class="alert alert-danger" role="alert">
			  @lang('Disabled product') <a wire:click="activateProduct" href="#">@lang('Activate')</a> 
			</div>
		@endif

    	@if(!$model->automatic_code)
			<div class="alert alert-danger" role="alert">
			  @lang('Automatic codes disabled') <a wire:click="activateCodesProduct" href="#">@lang('Activate')</a> 
			</div>
		@endif

		<div class="row">
			<div class="col-12 col-md-4">
			    <div class="card card-product_not_hover card-flyer-without-hover">

	                @if ($photo)
	                    @php
	                        try {
	                           $url = $photo->temporaryUrl();
	                           $photoStatus = true;
	                        }catch (RuntimeException $exception){
	                            $this->photoStatus =  false;
	                        }
	                    @endphp
	                    @if($photoStatus)
	                        <img class="card-img-top" alt="Responsive image" src="{{ $url }}">
	                        <br>
						  <ul class="list-group list-group-flush">
						    <li class="list-group-item">
		                        <div wire:loading.remove wire:target="photo"> 
								    <a href="#" wire:click="removePhoto" class="card-link">@lang('Cancel')</a>
								    <a href="#" wire:click="savePhoto" class="card-link pulsingButton">@lang('Save photo')</a>
								</div>
						    </li>
						  </ul>
							@error('photo') <span class="error p-2" style="color: red;"><p>{{ $message }}</p></span> @enderror
	                    @else
	                        @lang('Something went wrong while uploading the file.')
	                    @endif
                    @else
                    	@if($origPhoto)
			  	    	<img class="card-img-top" src="{{ asset('/storage/' . $origPhoto) }}" alt="Card image cap">
			  	    	@endif
				    @endif

					  <ul class="list-group list-group-flush no-print">
					    <li class="list-group-item">
                        	<div wire:loading wire:target="photo">@lang('Uploading')...</div>
							<div class="custom-file">
							  <input type="file" wire:model.lazy="photo" class="custom-file-input" id="customFile">
							  <label class="custom-file-label" for="customFile" data-browse="@lang('Select')">@lang('New photo')</label>
							</div>
					    </li>
					  </ul>

			      <div class="card-header text-center">
				    <h5 class="card-title">
				    	<strong>
				    
                  			<x-input.input-alpine nameData="isName" :inputText="$isName" :originalInput="$isName" wireSubmit="savename" modelName="name" maxlength="200" :extraName="__('Name')" />

				    	</strong>
				    </h5>
                    @error('name') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
				  </div>
			      
			      <div class="card-body">

	                <x-input.input-alpine nameData="isCode" :inputText="$isCode" :originalInput="$isCode" wireSubmit="savecode" modelName="code" :extraName="__('Code')" />

                    @error('code') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

	                <x-input.input-alpine nameData="isEditing" :inputText="$isDescription" :originalInput="$origDescription" wireSubmit="savedescription" maxlength="200" modelName="newDescription" />

			        <p class="card-text mt-3"><strong>@lang('Stock'): </strong>{{ $model->total_stock }}</p>

			        <p class="card-text"><strong>@lang('Line'):</strong> 
			            <x-utils.undefined :data="optional($model->line)->name"/>

					    <div x-data="{ show: false }" class="d-inline">
					        <button class="badge badge-light" @click="show = !show"> {{ $model->line_id ? __('Change line') : __('Choose line') }}</button>
					        {{-- <button class="badge badge-light {{ $model->line_id ?: 'pulsingButton'  }}" @click="show = !show"> {{ $model->line_id ? __('Change line') : __('Choose line') }}</button> --}}
					        <div x-show="show" class="mt-2" wire:ignore>
		                        <select id="lineselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
		                        </select>
					        </div>

			                @if($line_id)
						        <div x-show="show">
				                	<a role="button" wire:click="saveLine" class="btn btn-sm btn-primary float-right mt-2 text-white" type="submit">@lang('Save line')</a>
				                </div>
			                @endif
					    </div>
			        </p>

			        <hr width="50%;" style="border:1px dashed #9A68A9">

			        <p class="card-text"><strong>@lang('Brand'):</strong> 
			            
			            {{-- <x-utils.undefined :data="optional($model->brand)->name"/> --}}

                    	{!! $model->brand_id ? '<strong class="text-white bg-dark">'. optional($model->brand)->name  . '</strong>': '<span class="badge badge-secondary">'.__('undefined brand').'</span>' !!}


					    <div x-data="{ show: false }" class="d-inline">
					        <button class="badge badge-light " @click="show = !show"> {{ $model->brand_id ? __('Change brand') : __('Choose brand') }}</button>
					        <div x-show="show" class="mt-2" wire:ignore>
		                        <select id="brandselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
		                        </select>
					        </div>

			                @if($brand_id)
						        <div x-show="show">
				                	<a role="button" wire:click="saveBrand" class="btn btn-sm btn-primary float-right mt-2 text-white" type="submit">@lang('Save brand')</a>
				                </div>
			                @endif
					    </div>
			        </p>

			        <hr width="50%;" style="border:1px dashed #9A68A9">

			        <p class="card-text"><strong>@lang('Vendor'):</strong> 
			            
			            {{-- <x-utils.undefined :data="optional($model->vendor)->name"/> --}}

                    	{!! $model->vendor_id ? '<strong class="text-white bg-dark">'. optional($model->vendor)->name  . '</strong>': '<span class="badge badge-secondary">'.__('undefined vendor').'</span>' !!}


					    <div x-data="{ show: false }" class="d-inline">
					        <button class="badge badge-light " @click="show = !show"> {{ $model->vendor_id ? __('Change vendor') : __('Choose vendor') }}</button>
					        <div x-show="show" class="mt-2" wire:ignore>
		                        <select id="vendorselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
		                        </select>
					        </div>

			                @if($vendor_id)
						        <div x-show="show">
				                	<a role="button" wire:click="saveVendor" class="btn btn-sm btn-primary float-right mt-2 text-white" type="submit">@lang('Save vendor')</a>
				                </div>
			                @endif
					    </div>
			        </p>

			        <hr width="50%;" style="border:1px dashed #7C2E95">

			        <p class="card-text"><strong>@lang('Model'):</strong> 
			            <x-utils.undefined :data="optional($model->model_product)->name"/>

					    <div x-data="{ show: false }" class="d-inline">
					        <button class="badge badge-light " @click="show = !show"> {{ $model->model_product ? __('Change model') : __('Choose model') }}</button>
					        <div x-show="show" class="mt-2" wire:ignore>
		                        <select id="modelselect" class="custom-select" style="width: 100%;" aria-hidden="true" >
		                        </select>
					        </div>

			                @if($model_product)
						        <div x-show="show">
				                	<a role="button" wire:click="saveModel" class="btn btn-sm btn-primary float-right mt-2 text-white" type="submit">@lang('Save model')</a>
				                </div>
			                @endif
					    </div>
			        </p>

			        <p class="card-text no-print">

					    <div x-data="{ show: false }" class="d-inline">
					        <h5><button class="badge badge-info " @click="show = !show">@lang('Clone product')</button></h5>
					        <div x-show="show" class="mt-2">
	                          	<input type="text" class="form-control" placeholder="@lang('New code')" wire:model="code_clone">
					        </div>

			                @if($code_clone)
						        <div x-show="show">
				                	<a role="button" wire:click="clone" class="btn btn-sm btn-primary float-right mt-2 text-white" type="submit">@lang('Save')</a>
				                </div>
			                @endif
					    </div>

	                    @error('code_clone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror

			        </p>

			        <hr width="50%;" style="border:1px dashed #661181">

			        <p class="card-text" style="display: inline;"><strong>@lang('Price'): </strong><h4 style="display: inline;" class="text-primary">${{ $model->getPriceWithIvaApply($model->price ?? 0) }}</h4></p>
			        <p class="card-text"><strong>@lang('Price') @lang('without IVA'): </strong>${{ $model->price }}</p>

			        <p class="card-text"><strong>@lang('Provider price, without IVA'): </strong>${{ $model->cost }}</p>

			        <hr style="border:1px dashed #FFB03F">
					<div class="card p-4 border">
				        <h4>@lang('Manufacturing')</h4>

	          			<x-input.input-alpine nameData="isPriceMaking" :inputText="$isPriceMaking" :originalInput="$isPriceMaking" wireSubmit="savepricemaking" :beforeName="'$'" :extraName="__('price')" modelName="price_making" />

	          			<x-input.input-alpine nameData="isPriceMakingExtra" :inputText="$isPriceMakingExtra" :originalInput="$isPriceMakingExtra" wireSubmit="savepricemakingextra" :beforeName="'$'" :extraName="__('extra size')" modelName="price_making_extra" />
	          		</div>

			        <p class="card-text mt-4"><strong>@lang('Updated at'): </strong>{{ $model->updated_at }}</p>
			        <p class="card-text"><strong>@lang('Created at'): </strong>{{ $model->created_at }}</p>

				  	<a href="{{ route('frontend.shop.show', $model->slug) }}" class="card-link no-print" target="_blank">
				  		@lang('Show in store') <i class="cil-external-link"></i>
				  	</a>

			        {{-- <a href="#" class="btn btn-primary pulsingButton">@lang('Edit')</a> --}}
			      </div>
		          <div class="card-body no-print">
					  <ul class="list-group list-group-flush">
					    <li class="list-group-item">
						  <a href="{{ route('admin.product.advanced', $model->id) }}" class="card-link">@lang('Advanced information') {!! $model->status_advanced !!}</a>
					    </li>
					    <li class="list-group-item">
						  <a href="{{ route($nameStock ? 'admin.store.product.prices' : 'admin.product.prices', $model->id) }}" class="card-link">@lang('Prices and codes')</a>
					    </li>
					    <li class="list-group-item">
						  <a href="{{ route('admin.product.pictures', $model->id) }}" class="card-link">@lang('Images') <span class="badge bg-danger text-white">{{ ltrim($model->total_pictures, '0') }}</span> </a>
					    </li>
					    <li class="list-group-item">
						  <a href="{{ route('admin.product.move', $model->id) }}" class="card-link">@lang('Move between stocks')</a>
					    </li>
					    <li class="list-group-item">
						  <a href="{{ route('admin.product.delete-attributes', $model->id) }}" class="card-link">@lang('Delete attributes')</a>
					    </li>
					    <li class="list-group-item">
						  <a href="{{ route('admin.product.kardex', $model->id) }}" class="card-link">@lang('Kardex')</a>
					    </li>
					  </ul>
		                {{-- <x-input.rich-text wire:model.lazy="about" id="about" :initial-value="$about" /> --}}
			      </div>
			    </div>
			</div>

  			<div class="col-12 col-sm-6 col-md-8">

				@if(!$model->children->count())
					<form wire:submit.prevent="storemultiple" class="no-print">

		                <div class="form-group row" wire:ignore>
		                    <label for="colorselectmultiple" class="col-sm-2 col-form-label">@lang('Colors')</label>

		                    <div class="col-sm-10" >
		                        <select id="colorselectmultiple" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true" >
		                        </select>
		                    </div>
		                </div><!--form-group-->


		                <div class="form-group row" wire:ignore>
		                    <label for="sizeselectmuliple" class="col-sm-2 col-form-label">@lang('Sizes')</label>

		                    <div class="col-sm-10" >
		                        <select id="sizeselectmuliple" multiple="multiple" class="custom-select" style="width: 100%;" aria-hidden="true">
		                        </select>
		                    </div>
		                </div><!--form-group-->
		                @if($colorsmultiple_id && $sizesmultiple_id)
		                	<button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
		                @endif

		            </form>
                @else 
	  				<div class="row no-print">
		  				<div class="col-12">
		  					<h5> @lang('Colors'): 
		
							    <div style="display:inline-block;" 
							        x-data="
							            {
							                 isNewColor: false,
							            }
							        "
							        x-cloak
							    >
						            <div
							            x-show=!isNewColor
							        >
										<span class="badge bg-success text-white" x-on:click="isNewColor = true; $nextTick(() => focus())"> <i class="cil-plus"></i> </span>

								    </div>

							        <div x-show=isNewColor >
							            <form class="flex" wire:submit.prevent="savecolor">

											<div class="input-group w-80 input-group-sm">
										    	<div wire:ignore x-on:keydown.escape="isNewColor = false">
									     			<select  id="colorselect"  class="custom-select" aria-hidden="true" required style="width: 180px; ">
									        		</select>
									    		</div>

										    	<div class="input-group-append input-group-sm">
												    <span class="input-group-text" x-on:click="isNewColor = false">
												    	<i class="cil-x"></i>
												    </span>
				
											 		<button class="btn btn-primary" x-on:click="isNewColor = false" type="submit"><i class="cil-check-alt"></i></button>

											  	</div>
											</div>
							    		</form>
							        </div>
							    </div>

		  						@foreach($attributes->children->unique('color_id')->sortBy('color.name') as $children) 	
									<span class="badge text-white {{ in_array($children->color_id, $filters) ? 'bg-primary' : 'bg-dark' }}" 
						                  wire:click="$emit('filterByColor', {{ $children->color_id }})"
										  style="cursor:pointer"
									>{!! optional($children->color)->name_strikethrough !!} 
										@if(optional($children->color)->color)
											<div class="box-color-discrete justify-content-md-center" style="background-color:{{ $children->color->color }}; display: inline-block;"></div>
										@endif
										@if(optional($children->color)->secondary_color)
											<div class="box-color-discrete justify-content-md-center" style="background-color:{{ $children->color->secondary_color }}; display: inline-block;"></div>
										@endif
									</span>
								@endforeach
							</h5>
						</div>

		  				<div class="col-12 mt-2">
		  					<h5> @lang('Sizes'):
						    <div style="display:inline-block;" 
						        x-data="
						            {
						                 isNewSize: false,
						            }
						        "
						        x-cloak
						    >
					            <div
						            x-show=!isNewSize
						        >
									<span class="badge bg-success text-white" x-on:click="isNewSize = true; $nextTick(() => focus())"> <i class="cil-plus"></i> </span>

							    </div>

						        <div x-show=isNewSize >
						            <form class="flex" wire:submit.prevent="savesize">

										<div class="input-group w-80 input-group-sm">
									    	<div wire:ignore x-on:keydown.escape="isNewSize = false">
								     			<select  id="sizeselect"  class="custom-select" aria-hidden="true" required style="width: 180px; ">
								        		</select>
								    		</div>

									    	<div class="input-group-append input-group-sm">
											    <span class="input-group-text" x-on:click="isNewSize = false">
											    	<i class="cil-x"></i>
											    </span>
			
										 		<button class="btn btn-primary"  x-on:click="isNewSize = false" type="submit"><i class="cil-check-alt"></i></button>

										  	</div>
										</div>
						    		</form>
						        </div>
						    </div>

	  						@foreach($attributes->children->unique('size_id')->sortBy('size.sort') as $children) 	
								<span class="badge text-white {{ in_array($children->size_id, $filtersz) ? 'bg-primary' : 'bg-dark' }}" 
					                  wire:click="$emit('filterBySize', {{ $children->size_id }})"
									  style="cursor:pointer"
								>{{ optional($children->size)->name_strikethrough }}</span>
							@endforeach

							</h5>
						</div>
					</div>
				@endif

				@if($model->children->count())
	  				<div class="row mt-2 no-print">
		  				<div class="col-9">

						<table class="table table-borderless table-responsive">
						  <thead class="border-bottom border-start">
						    <tr>
						      <th scope="col">@lang('Action')</th>
						      @if(($nameStock === 'stock') or is_null($nameStock))
							      <th scope="col">@lang('Workshop')</th>
							  @endif
						      {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
							      <th scope="col">@lang('S.R.I')</th>
							  @endif --}}
						      @if(($nameStock === 'store_stock') or is_null($nameStock))
							      <th scope="col">@lang('Store stock')</th>
							  @endif
						    </tr>
						  </thead>
						  <tbody>
						    <tr class="">
						      <th scope="row"><span class="text-monospace text-success"><u>@lang('Input')</u></span></th>
						      @if(($nameStock === 'stock') or is_null($nameStock))
							      <td>
									<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-success">
				                          <input type="checkbox" class="c-switch-input" wire:model="increaseStock">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif
						      {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
							      <td>
							      	<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-success">
				                          <input type="checkbox" class="c-switch-input" wire:model="increaseStockRevision">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif --}}
						      @if(($nameStock === 'store_stock') or is_null($nameStock))
							      <td>
							      	<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-success">
				                          <input type="checkbox" class="c-switch-input" wire:model="increaseStockStore">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif
						    </tr>
						    <tr class="">
						      <th scope="row"><span class="text-monospace text-danger"><u>@lang('Output')</u></span></th>
						      @if(($nameStock === 'stock') or is_null($nameStock))
							      <td>
							      	<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-danger">
				                          <input type="checkbox" class="c-switch-input" wire:model="subtractStock">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif
						      {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
							      <td>
							      	<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-danger">
				                          <input type="checkbox" class="c-switch-input" wire:model="subtractStockRevision">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif --}}
						      @if(($nameStock === 'store_stock') or is_null($nameStock))
							      <td>
							      	<div class="custom-control custom-switch custom-control-inline">
				                        <label class="c-switch c-switch-danger">
				                          <input type="checkbox" class="c-switch-input" wire:model="subtractStockStore">
				                          <span class="c-switch-slider"></span>
				                        </label>
									</div>
							      </td>
						      @endif
						    </tr>
						  </tbody>
						</table>

						</div>

		  				<div class="col-3">
		  					<div class="d-flex justify-content-left">
					            <button type="button" class="btn btn-outline-dark btn-sm text-monospace font-weight-bold" wire:click="clearAll">
					            	<u>@lang('Clear filters')</u>
					            </button>

                                <input class="btn btn-primary disabled ml-2" aria-disabled="true" type="button" value="{{ __('Print') }}"
                                   onclick="window.print()" />

					        </div>
				        </div>

		  				<div class="col-12">
							<table class="table">
							  <thead>
							    <tr class="text-center">
							      <th scope="col">
								    <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
								      <em class=" mt-2"> @lang('Show labels')</em>
								        <div class="col-md-2 mt-2">
								          <div class="form-check">
								            <label class="c-switch c-switch-label c-switch-primary">
								              <input type="checkbox" wire:model="showLabels" class="c-switch-input">
								              <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
								            </label>
								          </div>
								        </div>
								    </div>
							      </th>
							      <th scope="col">
								    <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
								      <em class=" mt-2"> @lang('Show codes')</em>
								        <div class="col-md-2 mt-2">
								          <div class="form-check">
								            <label class="c-switch c-switch-label c-switch-primary">
								              <input type="checkbox" wire:model="showCodes" class="c-switch-input">
								              <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
								            </label>
								          </div>
								        </div>
								    </div>
							      </th>
							    </tr>
							  </thead>
							</table>
		  				</div>

		  				@if(!$nameStock)
		  				<div class="col-12">
							<table class="table">
							  <thead>
							    <tr class="text-center">
							      <th scope="col">
								    <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
								      <em class=" mt-2"> @lang('Show kardex')</em>
								        <div class="col-md-2 mt-2">
								          <div class="form-check">
								            <label class="c-switch c-switch-label c-switch-primary">
								              <input type="checkbox" wire:model="showKardex" class="c-switch-input">
								              <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
								            </label>
								          </div>
								        </div>
								    </div>
							      </th>
							      <th scope="col">
								    <div class="row justify-content-md-center custom-control custom-switch custom-control-inline">
								      <em class=" mt-2">@lang('I want to see the specific consumptions')</em>
								        <div class="col-md-2 mt-2">
								          <div class="form-check">
								            <label class="c-switch c-switch-label c-switch-warning">
								              <input type="checkbox" wire:model="showSpecificConsumptions" class="c-switch-input">
								              <span class="c-switch-slider" data-checked="OK" data-unchecked="NO"></span>
								            </label>
								          </div>
								        </div>
								    </div>
							      </th>
							    </tr>
							  </thead>
							</table>
		  				</div>
		  				@endif

			        </div>
				@endif

				@if($model->children->count())

				  	@foreach($model->children->sortBy('color.name')->groupBy('color_id') as $colorId => $childrens)
				    <div class="card card-box edit-product mt-4" style="{{ optional($childrens->first()->color)->color ? 'border: '. $childrens->first()->color->color. ' 3px solid' : '' }}">
				      <div class="card-body">

					    <h5 class="card-title">
					    	<strong>{!! optional($childrens->first()->color)->name_strikethrough !!}</strong>
					    	{!! optional($childrens->first()->color)->visual_color !!}
					    	@if($childrens->first()->color->color)
								<div class="box-color justify-content-md-center" style="background-color:{{ $childrens->first()->color->color }}; display: inline-block;"></div>
							@endif
					    	@if($childrens->first()->color->secondary_color)
								<div class="box-color justify-content-md-center" style="background-color:{{ $childrens->first()->color->secondary_color }}; display: inline-block;"></div>
							@endif
					    </h5>

						<div class="table-responsive">
						<table class="table table-sm">
						  <thead>
						    <tr>
						      @if($showCodes)
							      <th scope="col">@lang('Code')</th>
							  @endif
						      @if($showLabels)
							      <th scope="col">@lang('Labels')</th>
							  @endif
						      @if($showKardex)
							      <th scope="col">@lang('Kardex')</th>
							  @endif
						      <th scope="col">@lang('Color')</th>
						      <th scope="col">@lang('Size_')</th>
						      @if(($nameStock === 'stock') or is_null($nameStock))
							      <th scope="col" class="text-center">@lang('Workshop')</th>
						      @endif
						      {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
							      <th scope="col" class="text-center">@lang('Revision stock')</th>
						      @endif --}}
						      @if(($nameStock === 'store_stock') or is_null($nameStock))
							      <th scope="col" class="text-center">@lang('Store stock')</th>
						      @endif
						      @if($increaseStock == TRUE)
							      <th scope="col">@lang('Input')</th>
						      @endif
						      {{-- @if($increaseStockRevision == TRUE)
							      <th scope="col">@lang('Input revision stock')</th>
						      @endif --}}
						      @if($increaseStockStore == TRUE)
							      <th scope="col">@lang('Input store stock')</th>
						      @endif
						      @if($subtractStock == TRUE)
							      <th scope="col">@lang('Output')</th>
						      @endif
						      {{-- @if($subtractStockRevision == TRUE)
							      <th scope="col">@lang('Output revision stock')</th>
						      @endif --}}
						      @if($subtractStockStore == TRUE)
							      <th scope="col">@lang('Output store stock')</th>
						      @endif
						      <th>
						      </th>
						    </tr>

						  </thead>
						  <tbody class="group-{{ $colorId }}">
						        @foreach($childrens->sortBy('size.sort') as $children)

								    <tr>
								      @if($showCodes)
									      <td>{!! $children->code_subproduct !!}</td>
									  @endif
								      @if($showLabels)
									      <td>
									          <a href="{{ route('admin.product.large-barcode', $children->id) }}" target="_blank"><span class='badge badge-dark'><i class="cil-print"></i> @lang('Large')</span></a>
									          <a href="{{ route('admin.product.short-barcode', $children->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i> @lang('Short')</span></a>
									          <a href="{{ route('admin.product.packing-barcode', $children->id) }}" target="_blank"><span class='badge badge-info'><i class="cil-print"></i> @lang('Packing')</span></a>
									      </td>
									  @endif
								      @if($showKardex)
									      <td>
									      	<h4>
									          <a href="{{ route('admin.product.kardex', $children->id) }}" target="_blank"><span class='badge badge-light'><i class="cil-notes"></i> @lang('Kardex')</span></a>
									      	</h4>
									      </td>
									  @endif
								      <td style="{{ $children->trashed() ? 'text-decoration: line-through;' : '' }}">{!! optional($children->color)->name_strikethrough!!} {!! optional($children->color)->undefined_icon_coding !!}</td>
								      <td style="{{ $children->trashed() ? 'text-decoration: line-through;' : '' }}">{!! optional($children->size)->name_strikethrough!!} {!! optional($children->size)->undefined_icon_coding !!}</td>
								      @if(($nameStock === 'stock') or is_null($nameStock))
									      <td class="text-center {{ $children->color_stock($children->stock) }}">{{ $children->stock }}</td>
									  @endif
								      {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
								      	<td class="text-center {{ $children->color_stock($children->stock_revision) }}">{{ $children->stock_revision }}</td>
									  @endif --}}
								      @if(($nameStock === 'store_stock') or is_null($nameStock))
									      <td class="text-center {{ $children->color_stock($children->stock_store) }}">{{ $children->stock_store }}</td>
									  @endif
								      @if($increaseStock == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-valid sum-{{ $colorId }}" style="background-image: none; padding-right: inherit;" wire:model.defer="inputincrease.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="+" required>
									      </td>
									  @endif

								      {{-- @if($increaseStockRevision == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-valid sumrevision-{{ $colorId }}" style="background-image: none; padding-right: inherit;" wire:model.defer="inputincreaserevision.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="+" required>
									      </td>
									  @endif --}}

								      @if($increaseStockStore == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-valid sumstore-{{ $colorId }}" style="background-image: none; padding-right: inherit;" wire:model.defer="inputincreasestore.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="+" required>
									      </td>
									  @endif

								      @if($subtractStock == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-invalid sumsubtract-{{ $colorId }}" style="background-image: none; padding-right: inherit;" wire:model.defer="inputsubtract.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="-" required>
									      </td>
									  @endif

								      {{-- @if($subtractStockRevision == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-invalid" style="background-image: none; padding-right: inherit;" wire:model.defer="inputsubtractrevision.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="-" required>
									      </td>
									  @endif --}}

								      @if($subtractStockStore == TRUE)
									      <td style="width:100px; max-width: 100px;">
									      	<input class="form-control form-control-sm is-invalid sumstoresubtract-{{ $colorId }}" style="background-image: none; padding-right: inherit;" wire:model.defer="inputsubtractstore.{{ $children->id }}.stock" wire:keydown.enter="increase" type="number" min="1" placeholder="-" required>
									      </td>
									  @endif

									  {{-- @if(($showSpecificConsumptions == FALSE) && is_null($nameStock))
									  <td class="no-print">
									  	<div x-data="{ highlightedButton: '' }" style="display:inline;">
										    <a @click="highlightedButton='order'"  :class="{'badge-danger': highlightedButton === 'order'}" onmousedown="party.sparkles(this)" class="badge badge-primary text-white" wire:click="addToCart({{ $children->id }}, 'products')" ><i class="cil-cart"> </i> @lang('Order')</a>
										</div>
										<div x-data="{ highlightedButton2: '' }"  style="display:inline;">
										    <a @click="highlightedButton2='sale'"  :class="{'badge-danger': highlightedButton2 === 'sale'}" onmousedown="party.confetti(this)" class="badge badge-success text-white" wire:click="addToCart({{ $children->id }}, 'products_sale')" ><i class="cil-cart"> </i> @lang('Sale')</a>
										</div>
									  </td>
									  @endif --}}

									  @if($showSpecificConsumptions == TRUE)
									  <td>
									      <x-utils.link class="badge badge-warning text-white" :href="route('admin.product.consumption_filter', $children->id)" :text="__('Punctual consumption')" :target="true"/>
									  </td>
									  @endif

								    </tr>
							    @endforeach

				                <tr class="font-weight-bold">
	                            	<td colspan="2"></td>
							      	@if($showCodes)
		                            	<td>
		                        		</td>
	                        		@endif
							      	@if($showLabels)
		                            	<td>
		                        		</td>
	                        		@endif
							      	@if($showKardex)
		                            	<td>
		                        		</td>
	                        		@endif

							        @if(($nameStock === 'stock') or is_null($nameStock))
					                    <td class="text-center">{{ $model->getTotalByTypeStock($children->color_id, 'stock') }}</td>
					                @endif
							        {{-- @if(($nameStock === 'revision_stock') or is_null($nameStock))
					                    <td class="text-center">{{ $model->getTotalByTypeStock($children->color_id, 'stock_revision') }}</td>
					                @endif --}}
							        @if(($nameStock === 'store_stock') or is_null($nameStock))
					                    <td class="text-center">{{ $model->getTotalByTypeStock($children->color_id, 'stock_store') }}</td>
					                @endif
					                @if($increaseStock == TRUE)
						                <td>
						                	<div wire:ignore>
							                	<span id="total-{{ $colorId }}" class="total-span text-center">0</span>
							                </div>
							            </td>
						            @endif
					                {{-- @if($increaseStockRevision == TRUE)
						                <td>
						                	<div wire:ignore>
							                	<span id="totalrevision-{{ $colorId }}" class="total-span">Total: 0</span>
							                </div>
							            </td>
						            @endif --}}
					                @if($increaseStockStore == TRUE)
						                <td>
						                	<div wire:ignore>
							                	<span id="totalstore-{{ $colorId }}" class="total-span">0</span>
							                </div>
							            </td>
						            @endif

					                @if($subtractStock == TRUE)
						                <td>
						                	<div wire:ignore>
							                	<span id="totalsubtract-{{ $colorId }}" class="total-span">0</span>
							                </div>
							            </td>
						            @endif

					                @if($subtractStockStore == TRUE)
						                <td>
						                	<div wire:ignore>
							                	<span id="totalstoresubtract-{{ $colorId }}" class="total-span">0</span>
							                </div>
							            </td>
						            @endif
				                </tr>

						  </tbody>
						</table>

						</div>
				        {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}

				        {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
				      </div>

					  {{-- <div class="card-footer text-muted">
					    Total
					  </div> --}}
				    </div>
				    @endforeach
			    @endif
			</div>
		</div>
    <div class="layout-switcher no-print" tabindex="1">
      <div class="layout-switcher-head d-flex justify-content-between">
        <span>Acceso directo &nbsp;</span>
		<i class="cil-chevron-top"></i>
      </div>
      <div class="layout-switcher-body">

        <div class="layout-switcher-option active">
          <a href="#" class="text-white text-center">
            <i class="cil-text-square"></i>
          	Ficha técnica
          </a>
        </div>

        
      </div>
  	</div>

	</x-slot>
    <x-slot name="footer">
    	<em class="no-print">
	        <x-utils.delete-button :text="__('Delete product')" :href="route('admin.product.destroy', $model->id)" />
	    </em>
		<footer class="float-right no-print">
			@if($model->automatic_code)
				<a wire:click="desactivateCodesProduct" href="#">@lang('Disable automatic codes')</a> 
			@endif
			@if($model->status)
				<a wire:click="desactivateProduct" class="ml-3" href="#">@lang('Disable product')</a> 
			@endif
		</footer>
	</x-slot>
</x-backend.card> 

@push('after-scripts')
    <script>
	  // $.fn.select2.defaults.set( "theme", "bootstrap4" );
      $(document).ready(function() {
        $('#colorselect').select2({
          placeholder: '@lang("Choose color")',
          // width: 'resolve',
          theme: 'classic',
		  // containerCssClass: ':all:',
          // allowClear: true,
          ajax: {
                url: '{{ route('admin.color.select') }}',
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
                                text:  item.name + (item.color ? ' <div class="box-color justify-content-md-center" style="background-color:' + item.color +'; display: inline-block;"></div> ' : '') + (item.secondary_color ? ' <div class="box-color justify-content-md-center" style="background-color:' + item.secondary_color +'; display: inline-block;"></div> ' : '') + (item.short_name ? item.short_name.sup() : '')
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
            },

            escapeMarkup: function(m) { return m; }

          });

          $('#colorselect').on('change', function (e) {
              @this.set('color_id_select', e.target.value);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselect').select2({
          placeholder: '@lang("Choose size")',
          // width: 'resolve',
          theme: 'classic',
          // allowClear: true,
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

          $('#sizeselect').on('change', function (e) {
              @this.set('size_id_select', e.target.value);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#colorselectmultiple').select2({
          maximumSelectionLength: 10,
          closeOnSelect: false,
          placeholder: '@lang("Choose colors")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          multiple: true,
          ajax: {
                url: '{{ route('admin.color.select') }}',
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
                                text:  item.name + (item.color ? ' <div class="box-color justify-content-md-center" style="background-color:' + item.color +'; display: inline-block;"></div> ' : '') + (item.short_name ? item.short_name.sup() : '')
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
            },

            escapeMarkup: function(m) { return m; }

          });

          $('#colorselectmultiple').on('change', function (e) {
            var data = $('#colorselectmultiple').select2("val");
            @this.set('colorsmultiple_id', data);
          });
      });
    </script>

    <script>
      $(document).ready(function() {
        $('#sizeselectmuliple').select2({
          maximumSelectionLength: 10,
          closeOnSelect: false,
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

    <script>
      $(document).ready(function() {
        $('#lineselect').select2({
          placeholder: '@lang("Choose line")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.line.select') }}',
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

          $('#lineselect').on('change', function (e) {
            var data = $('#lineselect').select2("val");
            @this.set('line_id', data);
          });

      });
    </script>

    <script>
      $(document).ready(function() {
        $('#brandselect').select2({
          placeholder: '@lang("Choose brand")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.brand.select') }}',
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

          $('#brandselect').on('change', function (e) {
            var data = $('#brandselect').select2("val");
            @this.set('brand_id', data);
          });
      });
    </script>

    <script>
      $(document).ready(function() {
        $('#vendorselect').select2({
          placeholder: '@lang("Choose vendor")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.vendor.select') }}',
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

          $('#vendorselect').on('change', function (e) {
            var data = $('#vendorselect').select2("val");
            @this.set('vendor_id', data);
          });
      });
    </script>
    <script>
      $(document).ready(function() {
        $('#modelselect').select2({
          placeholder: '@lang("Choose model")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.model.select') }}',
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

          $('#modelselect').on('change', function (e) {
            var data = $('#modelselect').select2("val");
            @this.set('model_product', data);
          });
      });
    </script>

	<script>
	    document.addEventListener("DOMContentLoaded", () => {
	        $(function () {
	            $('[data-toggle="tooltip"]').tooltip()
	        })

	        Livewire.hook('message.processed', (message, component) => {
	            $(function () {
	                $('[data-toggle="tooltip"]').tooltip()
	            })
	        })
	    });
	</script> 

	<script type="text/javascript">
	    document.addEventListener('DOMContentLoaded', function () {
	        // Función para sumar valores y actualizar el total
	        function sumAll(colorId, className, totalId) {
	            var textboxes = document.querySelectorAll("." + className + "-" + colorId);
	            var total = 0;
	            textboxes.forEach(function(box) {
	                var val = box.value === "" ? 0 : parseInt(box.value);
	                total += val;
	            });
	            document.getElementById(totalId + "-" + colorId).innerText = total;
	        }

	        // Función para agregar event listeners a los inputs
	        function addEventListeners(colorId, className, totalId) {
	            var textboxes = document.querySelectorAll("." + className + "-" + colorId);
	            textboxes.forEach(function(box) {
	                box.addEventListener("keyup", function() {
	                    sumAll(colorId, className, totalId);
	                });
	            });
	        }

	        // Escuchar eventos emitidos desde Livewire
	        Livewire.on('triggerDOMContentLoaded', function () {
	            document.querySelectorAll("[class^='group-']").forEach(function(group) {
	                var colorId = group.classList[0].split('-')[1];
	                addEventListeners(colorId, 'sum', 'total');
	                sumAll(colorId, 'sum', 'total');
	            });
	        });

	        Livewire.on('triggerDOMContentLoadedRevision', function () {
	            document.querySelectorAll("[class^='group-']").forEach(function(group) {
	                var colorId = group.classList[0].split('-')[1];
	                addEventListeners(colorId, 'sumrevision', 'totalrevision');
	                sumAll(colorId, 'sumrevision', 'totalrevision');
	            });
	        });

	        Livewire.on('triggerDOMContentLoadedStore', function () {
	            document.querySelectorAll("[class^='group-']").forEach(function(group) {
	                var colorId = group.classList[0].split('-')[1];
	                addEventListeners(colorId, 'sumstore', 'totalstore');
	                sumAll(colorId, 'sumstore', 'totalstore');
	            });
	        });


	        Livewire.on('triggerDOMContentLoadedSubtract', function () {
	            document.querySelectorAll("[class^='group-']").forEach(function(group) {
	                var colorId = group.classList[0].split('-')[1];
	                addEventListeners(colorId, 'sumsubtract', 'totalsubtract');
	                sumAll(colorId, 'sumsubtract', 'totalsubtract');
	            });
	        });

	        Livewire.on('triggerDOMContentLoadedStoreSubtract', function () {
	            document.querySelectorAll("[class^='group-']").forEach(function(group) {
	                var colorId = group.classList[0].split('-')[1];
	                addEventListeners(colorId, 'sumstoresubtract', 'totalstoresubtract');
	                sumAll(colorId, 'sumstoresubtract', 'totalstoresubtract');
	            });
	        });

	    });
	</script>

    {{-- <script>
    	document.querySelector(".button").addEventListener("click", function (e) {
   			party.sparkles(this, {
        		count: party.variation.range(20, 40),
    		});
		});
    </script> --}}
@endpush