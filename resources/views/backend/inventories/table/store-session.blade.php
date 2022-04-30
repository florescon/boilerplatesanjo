<x-backend.card>
	<x-slot name="header">
        @lang('Make store inventory')
 	</x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.inventory.index')" :text="__('Back')" />
 	</x-slot>
    <x-slot name="body">

        <div class="row" wire:ignore>
            <div class="col-md-12 mb-5" style="text-align: center;">
                <div id="reader" class="shadow" style="display: inline-block;"></div>
                <div class="empty"></div>
                {{-- <div id="scanned-result"></div> --}}
            </div>
        </div>

        <div class="row mb-4 justify-content-md-center">
            <div class="col-9">
              <div class="input-group">
                <input wire:model.debounce.350ms="searchTerm" class="input-search" type="text" placeholder="Buscar producto capturado..." />
                <span class="border-input-search"></span>
              </div>
            </div>
            @if($searchTerm !== '')
            <div class="input-group-append">
              <button type="button" wire:click="clear" class="close" aria-label="Close">
                <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
              </button>

            </div>
            @endif
        </div>

        @if($session->count())

            <div class="table-responsive">
                <table class="table table-sm shadow">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">@lang('Product')</th>
                      <th scope="col" class="text-center">@lang('Captured')</th>
                      <th style="width: 200px;" class="text-center">@lang('Add')</th>
                      <th class="text-center">@lang('Updated at')</th>
                      <th class="text-center"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($session as $product)
                        <tr>
                          <th scope="row">{{ $product->id }}</th>
                          <td>{!! $product->product->full_name !!}</td>
                          <td class="text-center text-primary">{{ $product->capture }}</td>
                          <td>
                            <form 
                                class="form-inline justify-content-center" 
                                onkeydown="return event.key != 'Enter';" 
                                wire:submit.prevent="increase({{ $product->id }})"
                            >
                                <div class="input-group" style="max-width: 200px;">
                                    <input type="text" class="form-control" wire:model.defer="input.{{ $product->id }}.save">
                                    <button class="btn btn-primary" type="submit" > <i class="cil-save"></i> </button>
                                </div>
                            </form>
                          </td>
                          <td style="max-width: 100px;">{{ $product->updated_at ?? null }}</td>
                          <td>
                            <div x-data="{ open: false }">
                                <button class="btn btn-secondary btn-sm" @click="open = true">@lang('Show more')...</button>
                                <ul class="list-group" x-show="open" @click.outside="open = false">
                                    <li class="list-group-item"><button type="button" class="btn btn-danger btn-sm" wire:click="destroy({{ $product->id }})">@lang('Yes, Delete')</button></li>
                                </ul>
                            </div>
                          </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $session->links() }}
            </div>

        @else
            <div class="card shadow">
              <div class="card-body text-center">
                Sin registros <strong class="text-danger"><i class="cil-sad"></i></strong>
              </div>
            </div>
        @endif

	</x-slot>
</x-backend.card>