
<div class="card shadow-primary p-3 mb-5 bg-white rounded">

	<div class="card-header text-white" style="background-image: url({{ asset('/ga/img/color.jpg') }});">
    <strong> @lang('List of statuses') </strong>
    <div class="card-header-actions mb-5">
       <em> @lang('Last request'): {{ now()->format('h:i:s') }} </em>
    </div>
	</div>

	<div class="card-body">

  <div class="row mb-4">
    <div class="col form-inline">
      @lang('Per page'): &nbsp;

      <select wire:model="perPage" class="form-control">
        <option>10</option>
        <option>25</option>
        <option>50</option>
        <option>100</option>
      </select>
    </div><!--col-->

    <div class="col">
      <div class="input-group">
        <input wire:model.debounce.350ms="searchTerm" class="form-control" type="text" placeholder="{{ __('Search') }}..." />
        @if($searchTerm !== '')
        <div class="input-group-append">
          <button type="button" wire:click="clear" class="close" aria-label="Close">
            <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
          </button>

        </div>
        @endif
      </div>
    </div>

    @if($selected && $statuses->count())
    <div class="dropdown table-export">
      <button class="dropdown-toggle btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @lang('Export')        
      </button>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" wire:click="export">CSV</a>
      </div>
    </div><!--export-dropdown-->
    @endif
  </div><!--row-->

	  <div class="row mt-4">
	    <div class="col">
	      <div class="table-responsive">
	        <table class="table table-sm align-items-center table-flush table-bordered table-hover js-table">
	          <thead style="color: #0061f2;">
	            <tr>

	              <th scope="col">
	                <a wire:click.prevent="sortBy('name')" role="button" href="#">
	                  @lang('Name')
	                  @include('backend.includes._sort-icon', ['field' => 'name'])
	                </a>
	              </th>
                <th scope="col">
                  @lang('Description')
                </th>
	              <th scope="col">
	                <a wire:click.prevent="sortBy('level')" role="button" href="#">
	                  @lang('Level')
	                  @include('backend.includes._sort-icon', ['field' => 'level'])
	                </a>
	              </th>
	              <th scope="col">
	                  @lang('Percentage')
	              </th>
	              <th scope="col">
	                  @lang('To add users')
	              </th>
	              <th scope="col">
	                  @lang('Updated')
	              </th>
	            </tr>
	          </thead>
	          <tbody>
	            @foreach($statuses as $status)
	            <tr >
                <td>
                  {{ $status->name }}
                </td>
	              <td>
	              	{{ $status->description }}
	              </td>
	              <td>
	              	{{ $status->level }}
	              </td>
	              <td>
		              {{ $status->percentage }}
	              </td>
	              <td>
	              	{!! $status->status_add_users !!}
	              </td>
	              <td>
									{{ $status->date_for_humans }}
	              </td>
	            </tr>
	            @endforeach
	          </tbody>
	        </table>

	        @if($statuses->count())
	        <div class="row">
	          <div class="col">
	            <nav>
	              {{ $statuses->links() }}
	            </nav>
	          </div>
	              <div class="col-sm-3 text-muted text-right">
	                Mostrando {{ $statuses->firstItem() }} - {{ $statuses->lastItem() }} de {{ $statuses->total() }} resultados
	              </div>
	        </div>
	        @else
	          @lang('No search results') 
	          @if($searchTerm)
	            "{{ $searchTerm }}" 
	          @endif

	          @if($page > 1)
	            {{ __('in the page').' '.$page }}
	          @endif
	        @endif

	      </div>

	    </div>
	  </div>
	</div>

</div>

@push('after-scripts')

@endpush