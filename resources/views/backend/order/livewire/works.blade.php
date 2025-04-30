<x-backend.card>
    <x-slot name="header">
        @lang('Show workstation') - <h3 class="d-inline">{{ ucfirst($status->name ?? ' ') }}</h3>
    </x-slot>

    <x-slot name="headerActions">
    </x-slot>
    <x-slot name="body">

		<div class="container-fluid">

		  <div class="row">
		    <!-- Contenido principal con margen derecho para el sidebar -->
		    <main class="col-md-9" style="margin-right: 320px;">
		      <!-- Tu contenido largo aquí -->
		    </main>
		    
		    <!-- Sidebar flotante -->
		    <aside class="position-fixed bg-light border-left" style="right: 4%; top: 19%; width: 17%; height: calc({{ $floatButton ? '64vh' : '10vh' }}); overflow-y: auto; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">


		      <div class="p-3">

	            <button wire:click="$toggle('floatButton')" class="form-control">
	              @if(!$floatButton)
	              	Maximizar
	              @else
	                Minimizar
	              @endif
	            </button>

		        <h5 class="mt-4">Menú Flotante</h5>
		          <!-- Más items... -->

		        <ul class="nav flex-column">

		          <li class="nav-item">
		            <a class="nav-link active" href="#">Activo: </a>
		          </li>
		          <li class="nav-item">
		            <a class="nav-link" href="#">Pendiente: </a>
		          </li>
		          <!-- Más items... -->
		        </ul>
		      </div>
		    </aside>
		  </div>
		</div>

      	<div class="row">
	        <div class="col-sm-9 mt-2">

				<div class="container-fluid mt-3">

@foreach($order->getSizeTablesData() as $parentId => $tableData)
    <div class="product-group shadow-sm p-3 mt-2">
        <h5 class=""> 
            <strong class="text-primary">{{ $tableData['parent_code'] }}</strong> 
            {{ $tableData['parent_name'] }}
        </h5>


        @if(is_int($parentId))
        <div class="text-right">
            <button 
                wire:click="saveByParent('{{ $parentId }}')" 
                wire:loading.attr="disabled"
                class="btn btn-sm btn-primary mb-2"
            >
                <span wire:loading.remove wire:target="saveByParent('{{ $parentId }}')">
                    <i class="fas fa-save"></i> Guardar {{ $tableData['parent_code'] }}
                </span>
                <span wire:loading wire:target="saveByParent('{{ $parentId }}')">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Guardando...
                </span>
            </button>
        </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        @if($tableData['rows'][0]['no_size'])
                        <th class="align-middle">Código</th>
                        @endif
                        <th class="align-middle" style="width: 250px;">Color</th>
                        @foreach($tableData['headers'] as $header)
                            <th class="text-center align-middle">{{ $header['name'] }}</th>
                        @endforeach
                        @if($tableData['rows'][0]['no_size'])
                            <th class="align-middle"></th>
                        @endif
                        <th class="text-center align-middle">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableData['rows'] as $rowIndex => $row)
                        <tr>
                            @if($row['no_size'])
                                <td style="width: 35%">{{ $row['general_code'] }}</td>
                            @endif
                            <td style="width: 10%">{{ $row['color_product'] ?: 'N/A' }}</td>
                            
            @foreach($tableData['headers'] as $header)
                <td class="text-center">
                    @if(isset($row['sizes'][$header['id']]))
                        {!! $row['sizes'][$header['id']]['only_display'] !!}
                        
                        <div class="d-inline-block position-relative">
                            <input 
                                type="number" 
                                class="form-control text-center form-control-sm @error('quantities.'.$parentId.'.'.$rowIndex.'.'.$header['id']) is-invalid @enderror" 
                                style="width: 60px;"
                                wire:model.lazy="quantities.{{ $parentId }}.{{ $rowIndex }}.{{ $header['id'] }}"
                                min="0"
                                max="{{ $row['sizes'][$header['id']]['quantity'] ?? 0 }}"
                                step="1"
                                data-size="{{ $header['id'] }}"
                                data-color="{{ $row['color_product'] }}"
                            >
                            @error('quantities.'.$parentId.'.'.$rowIndex.'.'.$header['id'])
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </td>
            @endforeach
                            
                            @if($row['no_size'])
                            <td class="text-center">
                                {!! $row['no_size']['only_display'] !!}
                            </td>
                            @endif
                            <td class="text-center font-weight-bold">
                                <div>{{ $row['row_quantity'] }}</div>
                            </td>
                        </tr>
                    @endforeach
                    
                    <!-- Totals row -->
                    <tr class="bg-light">
                        @if($tableData['rows'][0]['no_size'])
                            <td class="font-weight-bold"></td>
                        @endif
                        <td class="font-weight-bold"></td>
                        
                        @foreach($tableData['headers'] as $header)
                            <td class="text-center font-weight-bold text-dark">
                                @if(isset($tableData['totals']['size_totals'][$header['id']]))
                                    {{ $tableData['totals']['size_totals'][$header['id']]['quantity'] }}
                                @endif
                            </td>
                        @endforeach

                        @if($row['no_size'])                    
                        <td class="text-center font-weight-bold text-dark">
                            @if($tableData['totals']['no_size_total']['quantity'] > 0)
                                {{ $tableData['totals']['no_size_total']['quantity'] }}
                            @endif
                        </td>
                        @endif
                        <td class="text-center font-weight-bold text-danger">
                            <div>{{ $tableData['totals']['row_quantity'] }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach
</div>
</div>
</div>
</x-slot>
</x-backend.card>
