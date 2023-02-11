<x-backend.card>

	<x-slot name="header">
        @lang('Product images')
 	</x-slot>

  <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.dashboard')" :text="__('Cancel')" />
 	</x-slot>

  <x-slot name="body">

    @include('backend.setting.modal-banner')

    <div class="alert alert-warning text-center" role="alert">
      <h4 class="alert-heading">Tamaño máximo de 2MB, límite de {{ $countImages }} imágenes</h4>
      @error('files.*') <span class="error" style="color: red;">{{ $message }}</span> @enderror
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <x-input.filepond wire:model="files" multiple/>
        </div>
        <div class="col-12 col-sm-6 col-md-8">
            @if(!empty($files))
              <div class="card">
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                      <li class="list-group-item">
                          <div class="text-center"> 
                              <a href="#" wire:click="savePictures" class="btn btn-primary pulsingButton">@lang('Save photo(s)')</a>
                          </div>
                      </li>
                  </ul>
                </div>
              </div>
            @endif
            <div class="lightbox">

              <div class="col mb-4">
                <div class="input-group">
                  <input wire:model.debounce.350ms="searchTerm" class="form-control input-search-color" type="text" placeholder="{{ __('Search') }}..." />
                  @if($searchTerm !== '')
                  <div class="input-group-append">
                    <button type="button" wire:click="clear" class="close" aria-label="Close">
                      <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
                    </button>

                  </div>
                  @endif
                </div>
              </div>

              <div class="row">
                @foreach($logos->split($logos->count()/1) as $picture)
                  <div class="col-lg-4">
                    @foreach($picture as $pic)
                        <div class="card">
                          <img class="card-img-top" src="{{ asset('/storage/' . $pic->image) }}" alt="Card image cap">
                          <div class="card-body text-center">                            

                            <livewire:backend.setting.edit-title :image="$pic" :key="$pic->id" :extraName="__('edit title without spaces')"/>

                            <div class="dropdown mt-2">
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

            @if($logos->count())
              <div class="row">
                <div class="col">
                  <nav>
                    {{ $logos->onEachSide(1)->links() }}
                  </nav>
                </div>
                    <div class="col-sm-3 mb-2 text-muted text-right">
                      Mostrando {{ $logos->firstItem() }} - {{ $logos->lastItem() }} de {{ $logos->total() }} resultados
                    </div>
              </div>
            @else

              @lang('No search results') 

              @if($searchTerm)
                "{{ $searchTerm }}" 
              @endif

              @if($deleted)
                @lang('for deleted')
              @endif

              @if($page > 1)
                {{ __('in the page').' '.$page }}
              @endif
            @endif

        </div>
    </div>
	</x-slot>
  
</x-backend.card>