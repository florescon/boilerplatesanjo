<x-backend.card>

	<x-slot name="header">
        @lang('Banner images')
 	</x-slot>

  <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.dashboard')" :text="__('Cancel')" />
 	</x-slot>

  <x-slot name="body">

    @include('backend.setting.modal-banner')

    <div class="alert alert-warning text-center" role="alert">
      <h4 class="alert-heading">Tamaño máximo de 1MB, límite de 8 imágenes</h4>

      <h5>
        <a href="#" class="card-header-action" style="color: green;" data-toggle="modal" data-target="#exampleModal"><i class="c-icon cil-plus"></i> @lang('Show details') </a>
      </h5>

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
              <div class="row">
                @foreach($logos->split($logos->count()/1) as $picture)
                  <div class="col-lg-4">
                    @foreach($picture as $pic)
                        <div class="card">
                          <img class="card-img-top" src="{{ asset('/storage/' . $pic->image) }}" alt="Card image cap">
                          <div class="card-body text-center">                            

                            <livewire:backend.setting.edit-sort :image="$pic" :key="$pic->id" :extraName="__('edit sort')"/>

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
        </div>
    </div>
	</x-slot>
  
</x-backend.card>