@push('after-styles')
  <style type="text/css">
  </style>
@endpush

<x-backend.card>

	<x-slot name="header">
        @lang('Product images')
 	</x-slot>

  <x-slot name="headerActions">

	    <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" />

        <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
 	</x-slot>

  <x-slot name="body">
    <div class="row ">

        <div class="col-12 col-md-4">
            <div class="card card-custom card-product_not_hover bg-white border-white border-0">
              <div class="card-body">
                <ul class="list-group list-group-flush">
                  @if(!empty($files))
                    <li class="list-group-item">
                        <div class="text-center"> 
                            <a href="#" wire:click="savePictures" class="btn btn-primary pulsingButton">@lang('Save photo(s)')</a>
                        </div>
                    </li>
                  @endif

                  <li class="list-group-item">
                    <h5 class="card-title"><strong>{{ $model->name }}</strong></h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $model->code }}</h6>
                  </li>

                  <li class="list-group-item">
                    <strong>@lang('Colors'): </strong> 
                    @foreach($model->childrenOnlyColors->unique('color_id')->sortBy('color.name') as $colors)
                      <button type="button" style="margin-top: 3px" class="btn {{ in_array($colors->color_id, $filters_c) ? 'btn-primary text-white' : 'btn-outline-primary' }} btn-sm" wire:click="$emit('filterByColor', {{ $colors->color_id }})">
                        {{ $colors->color->name }} <span class="badge bg-primary text-white">{{ ltrim($product_general->getTotalPicturesByColor($colors->color_id), '0') }}</span>
                      </button>
                    @endforeach
                  </li>
                </ul>
              </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-8">
          {{-- @error('files') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror --}}

            <div class="card text-center" style="border-color: {{ $color ?? 'white' }};">
              <div class="card-body">
                {{ $name_color }}
              </div>
            </div>


            <div class="lightbox">
              <div class="row">
                @foreach($model->pictures->split($model->pictures->count()/1) as $picture)
                  <div class="col-lg-4">
                    @foreach($picture as $pic)
                        <div class="card">
                          <img class="card-img-top" src="{{ asset('/storage/' . $pic->picture) }}" alt="Card image cap">
                          <div class="card-body text-center">
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('Action')
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <a class="dropdown-item" wire:click="removeFromPicture({{ $pic->id }})">
                                  @lang('Delete')
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                    @endforeach
                  </div>
                @endforeach
              </div>
            </div>

            <x-input.filepond wire:model="files" multiple />

        </div>
    </div>
	</x-slot>
</x-backend.card>