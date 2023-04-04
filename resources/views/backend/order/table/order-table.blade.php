<x-backend.card >

  <x-slot name="body">

      <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-12">
          <ul class="nav nav-tabs nav-fill mt-1" role="tablist">
            <li class="nav-item">
              <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('admin.order.all') }}" role="tab">@lang('all')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == 'quotations' ? 'active' : '' }}" href="{{ route('admin.order.quotations') }}" role="tab">@lang('Quotations')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == '' ? 'active' : '' }}" href="{{ route('admin.order.index') }}" role="tab">@lang('Orders')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == 'sales' ? 'active' : '' }}" href="{{ route('admin.order.sales') }}" role="tab">@lang('Sales')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == 'mix' ? 'active' : '' }}" href="{{ route('admin.order.mix') }}" role="tab">@lang('Mix')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $status == 'suborders' ? 'active' : '' }}" href="{{ route('admin.order.suborders') }}" role="tab">@lang('Outputs')</a>
            </li>
          </ul>

				  @if($status == 'suborders')
					  <div class="page-header-subtitle text-right mt-4 mb-2">
				      <x-utils.link
				      	style="color: purple;"
				        icon="c-icon cil-plus"
				        class="card-header-action"
				        :href="route('admin.order.createsuborder')"
				        :text="__('Create output')"
				      />
				    </div>
			    @endif

				  <div class="page-header-subtitle mt-4 mb-2">
				  	<em>
				  		@lang('Filter by updated date range')
				  	</em>
				  </div>

				  <div class="row input-daterange mb-3">
			      <div class="col-md-3 mb-2">
			        <x-input.date wire:model="dateInput" id="dateInput" borderClass="{{ $title['color'] }}" placeholder="{{ __('From') }}"/>
			      </div>

			      <div class="col-md-3 mb-2">
			        <x-input.date wire:model="dateOutput" id="dateOutput" borderClass="{{ $title['color'] }}" placeholder="{{ __('To') }}"/>
			      </div>
			      &nbsp;

			      <div class="col-md-5 mb-2 text-right">
			        <div class="btn-group" role="group" aria-label="First group">
			          <button type="button" class="btn btn-outline-{{ $title['color'] }}" wire:click="clearFilterDate"  class="btn btn-default">@lang('Clear date')</button>
			          <button type="button" class="btn btn-outline-{{ $title['color'] }}" wire:click="clearAll" class="btn btn-default">@lang('Clear all')</button>
			        </div>
			      </div>
			      &nbsp;
				  </div>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="teams" role="tabpanel" data-filter-list="content-list-body">
              <div class="row content-list-head">
                <div class="col-auto">
							    <div class="col form-inline">
							      @lang('Per page'): &nbsp;

							      <select wire:model="perPage" class="form-control ml-4 mb-2">
							        <option>5</option>
							        <option>10</option>
							        <option>25</option>
							        <option>50</option>
							        <option>100</option>
							      </select>

				  					@if($status == '' || $status == 'all')
											<div class="ml-4">
							          <livewire:backend.attributes.status-change/>
							        </div>
											@if($statusOrder)
												<button class="btn btn-danger btn-sm ml-4 pb-2 mb-2" wire:click="clearFilterStatusOrder">
													@lang('Clear status order')
												</button>
											@endif
							    	@endif
							    </div><!--col-->

                </div>
              </div>

              <div class="row content-list-head">
                <div class="col-auto">
                </div>
                <form class="col-lg-auto">
                  <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="cil-search"></i>
                      </span>
                    </div>
                    <input type="search" wire:model.debounce.350ms="searchTerm" class="form-control filter-list-input" placeholder="{{ __('Search by folio, tracking number or comment') }}" aria-label="{{ __('Search by folio, tracking number or comment') }}">
                  </div>
                </form>
              </div>
              <!--end of content list head-->
              <div class="content-list-body row">
						    <div class="col">
                    <div class="card-list">
                      <div class="card-list-head">
                        <h6>@lang($title['title'])</h6>
                        <div class="dropdown">
                          <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="cil-blur"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">@lang('Export') (@lang('Inactive'))</a>
                          </div>
                        </div>
                      </div>
						            @foreach($orders as $order)
		                      <div class="card card-task">
		                        <div class="progress">
		                          <div class="progress-bar" role="progressbar" style="width: {{ $order->last_status_order_percentage }}%; background-color: {{ $order->last_status_order_color }};" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
		                        </div>
		                        <div class="card-body">
		                          <div class="card-title">
		                            <a target="_blank" href="{{ route('admin.order.edit', $order->id) }}" style="text-decoration: none !important"><h6 data-filter-by="text"><strong>#{{ $order->id }}</strong> {!! $order->user_name !!} {!! Str::limit($order->info_customer, 100) ?? '' !!} </h6></a>
		                            <span class="text-small">{!! Str::limit($order->comment, 100) ?? '<span class="badge badge-secondary">'.__('undefined').'</span>' !!}</span>
		                            <span class="badge badge-dot">
								                  {!! $order->date_diff_for_humans_created !!}
								                </span>
		                          </div>
		                          <div class="card-meta">
		                            <div class="d-flex align-items-center">
		                              <span>{!! $order->last_status_order_label !!} {!! $order->to_stock_final !!} {!! $order->to_customer ? '<i class="cil-check" style="color: blue;"></i>' : '<i class="cil-minus" style="color:red;"></i>' !!}</span>
		                            </div>
		                            <div class="dropdown card-options">
		                              <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		                              	<i class="cil-options"></i>
		                              </button>
		                              <div class="dropdown-menu dropdown-menu-right">
		                                <a class="dropdown-item" target="_blank" href="{{ route('admin.order.edit', $order->id) }}">@lang('Show')</a>
		                                <div class="dropdown-divider"></div>
		                              </div>
		                            </div>
		                          </div>
		                        </div>
		                      </div>


						            @endforeach

						        @if($orders->count())
							        <div class="row">
							          <div class="col">
							            <nav>
							              {{ $orders->onEachSide(1)->links() }}
							            </nav>
							          </div>
							              <div class="col-sm-3 mb-2 text-muted text-right">
							                Mostrando {{ $orders->firstItem() }} - {{ $orders->lastItem() }} de {{ $orders->total() }} resultados
							              </div>
							        </div>
						        @else
						          @lang('No search results') 
						          @if($searchTerm)
						            "{{ $searchTerm }}" 
						          @endif

						          @if($dateInput) 
						            @lang('from') {{ $dateInput }} {{ $dateOutput ? __('To') .' '.$dateOutput : __('to this day') }}
						          @endif

						          @if($page > 1)
						            {{ __('in the page').' '.$page }}
						          @endif
						        @endif


						    </div>

              </div>
              <!--end of content-list-body-->
            </div>
          </div>
        </div>
      </div>

	</x-slot>

</x-backend.card>

@push('after-scripts')
	<script type="text/javascript">
		$(function () {
		    $(".js-table").on("click", "tr[data-url]", function () {
		        window.location = $(this).data("url");
		    });
		});
	</script>
@endpush