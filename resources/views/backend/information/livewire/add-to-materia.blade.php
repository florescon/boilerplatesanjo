<div class="container py-3">
    <div class="row">
        <div class="mx-auto col-sm-8">
            <!-- form user info -->
            <div class="card shadow-lg">
                <div class="card-header p-4">
                    <h4 class="mb-0">{{ ucfirst($status->name) }} - Global</h4>
                </div>

                  <div class="card-body">
                    <form class="form" role="form" autocomplete="off">
                  
                        <div class="text-center mb-4">
                            <a type="button" target="_blank" href="{{ route('admin.information.status.pending_materia', $status->id) }}" class="btn btn-outline-dark mr-4">Exportar pendiente de consumo</a>

                            {{-- <a href="#!" class="btn btn-primary" data-toggle="modal" data-target="#showModal" wire:click="show({{ $status->id }})"><small>@lang('Details')</small></a> --}}

                        </div>
             
                        <?php
                            $filteredMaterials = $materials->map(function ($groupedByVendor) {
                                return $groupedByVendor->filter(function ($material) {
                                    return ($material['stock'] - $material['quantity']) < 0;
                                });
                            })->filter(function ($groupedByVendor) {
                                return $groupedByVendor->isNotEmpty();
                            });
                        ?>

                        @foreach($filteredMaterials as $vendor => $material)

                            <div>

                              <table width="100%">

                                <thead>
                                    <div class="badge badge-primary text-wrap" style="width: 6rem;">
                                        {{ $vendor }}
                                    </div>
                                </thead>

                                <thead style="background-color: lightgray;">
                                  <tr>
                                    <th>#</th>
                                    <th>Internal Code</th>
                                    <th>Description</th>
                                    <th class="text-center">Existencia</th>
                                    <th class="text-center">Requerimiento</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->sortBy([['vendor', 'asc'], ['material_name', 'asc']]) as $key => $material)
                                        @if(($material['stock'] - $material['quantity']) < 0)
                                          <tr>
                                            <th scope="row">{{ $key+1 }}</th>
                                            <th>{{ $material['part_number'] }}</th>
                                            <td>{{  $material['material_name'] }}</td>
                                            <td class="text-center">{{  $material['stock'] }}</td>
                                            <td class="text-center">{{ (($material['stock'] - $material['quantity']) < 0) ?  (abs($material['stock'] - $material['quantity']) .' '.$material['unit_measurement']) : '' }}</td>
                                          </tr>
                                        @endif
                                    @endforeach

                              </table>

                            </div>
                            <br>
                        @endforeach

                        @if($getFeedstocks->count())
                        <div class=" border border-danger p-4 rounded">
                            @foreach ($getFeedstocks as $getFeedstock)

                                <div class="form-group row">
                                    <label class="col-lg-8 col-form-label form-control-label">
                                        <a wire:click="removeFeedstock({{ $getFeedstock->id }})" class="link link-dark-primary link-normal text-danger" style="cursor:pointer;" onclick="confirm('¿Seguro que desea eliminar este registro?') || event.stopImmediatePropagation()"><i class="fas fa-times text-c-blue m-l-10"></i></a> 

                                        {{ $getFeedstock->material->part_number }}
                                        -
                                        {!! optional($getFeedstock->material)->name !!}
                                    </label>
                                    <div class="col-lg-2 text-center">
                                        
                                    </div>
                                    <div class="col-lg-2 text-center">
                                        {{ $getFeedstock->quantity }} {{ optional($getFeedstock->material)->unit_name_label }}
                                    </div>
                                </div>

                            @endforeach


                            @if($getFeedstocks->count() >= 1)
                                <a href="#" wire:click="clearAllFeedstocks" onkeydown="return event.key != 'Enter';" class="btn btn-danger btn-sm mt-2">@lang('Clear feedstocks')</a>
                            @endif
                        </div>
                        @endif



                        <div class="form-group row pt-4">
                            <div class="col-lg-10 text-right">
                                <a type="reset" class="btn btn-secondary" href="{{ route('admin.information.status.show', $status->id) }}" value="Cancel">@lang('Cancel')</a>
                            </div>
                            <div class="col-lg-2 text-center">
                                <a type="button" target="_blank" href="{{ route('admin.information.status.pending_materia_grouped', [$status->id, true]) }}" class="btn btn-outline-dark mb-4">@lang('Generate')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /form user info -->
        </div>

        <div class="mx-auto col-sm-4">
            <!-- form user info -->
            <div class="card shadow-lg">
                <div class="card-header p-4">
                    <h4 class="mb-0">@lang('Search Feedstock')</h4>
                </div>

                <div class="card-body">
                    <a href="#!" data-toggle="modal" data-target="#searchProduct"><i class="fa fa-search mr-1 ml-1"></i> @lang('Add feedstock')</a>
                </div>
            </div>
            <!-- /form user info -->
        </div>

    </div>

    <livewire:backend.additional.search-feedstocks :typeSearch="'feedstock'" branchIdSearch="0"/>

</div>
