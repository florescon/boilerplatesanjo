<x-backend.card>
  <x-slot name="header">
        @lang('Delete attributes')
  </x-slot>

  <x-slot name="headerActions">
        {{-- <x-utils.link class="card-header-action btn btn-primary text-white" :href="route('admin.product.edit', $model->id)" :text="__('Go to edit product')" /> --}}
  </x-slot>

  <x-slot name="body">
      <div class="alert alert-danger text-center" role="alert">
        Estás a punto de eliminar un atributo del producto Product01. Al final que selecciones, te mostrará el botón de eliminar.
        <br>
        Esta acción es irrevocable 
      </div>

    <div class="row">
      <div class="col-12 col-md-6">
          <div class="card card-pricing popular shadow">
            <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-danger text-white shadow-sm"><h4>@lang('Colors')</h4></span>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item list-group-item-action">Cras justo odio</li>
                <li class="list-group-item list-group-item-action">Dapibus ac facilisis in</li>
                <li class="list-group-item list-group-item-action">Morbi leo risus</li>
                <li class="list-group-item list-group-item-action">Porta ac consectetur ac</li>
                <li class="list-group-item list-group-item-action">Vestibulum at eros</li>
              </ul>
            </div>
          </div>
      </div>
      <div class="col-12 col-md-6">
          <div class="card card-pricing popular shadow">
            <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-danger text-white shadow-sm"><h4>@lang('Sizes')</h4></span>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item list-group-item-action">Cras justo odio</li>
                <li class="list-group-item list-group-item-action">Dapibus ac facilisis in</li>
                <li class="list-group-item list-group-item-action">Morbi leo risus</li>
                <li class="list-group-item list-group-item-action">Porta ac consectetur ac</li>
                <li class="list-group-item list-group-item-action">Vestibulum at eros</li>
              </ul>
            </div>
          </div>
      </div>
    </div>

  </x-slot>

</x-backend.card>