	<div class="page-header">
		<div class="page-block" style="background: rgba(11,13,0,.5);">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="page-header-title">
						<h5 class="m-b-10"> {{ now()->format('l jS \of F') }}</h5>
						<p class="m-b-0">@lang('Welcome to daily cash closing panel') {{ Auth::user()->name }}</p>
					</div>
				</div>
				<div class="col-md-3">
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#!" data-toggle="modal" wire:click="$emitTo('backend.store.pos.create-cash', 'createmodal')" data-target="#createCash"> {{ $last_record_cash ? '$'.$last_record_cash['initial'] : __('Add initial daily cash closing') }} </a>
						</li>
					</ul>
				</div>
				@if($last_record_cash)
					<div class="col-md-3">
						<ul class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="#" wire:click="process">@lang('Process daily cash closing') #{{ $last_record_cash['id'] }} <i class="fa fa-caret-square-o-right mr-1 ml-1"></i></a>
							</li>
						</ul>
					</div>
				@endif
			</div>

			@if($process)
				<div class="row mt-2">
					<div class="col-md-5">
						<div class="input-group mb-2">
						  <div class="input-group-prepend">
						    <span class="input-group-text" id="">@lang('Title')</span>
						  </div>
						  <input wire:model="title" type="text" class="form-control">
						</div>
						@error('title') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
					</div>
					<div class="col-md-5">
						<div class="input-group mb-2">
						  <div class="input-group-prepend">
						    <span class="input-group-text" id="">@lang('Comment')</span>
						  </div>
						  <input wire:model="comment" type="text" class="form-control">
						</div>
						@error('comment') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
					</div>
					<div class="col-md-2">
						<button type="button" wire:click="processDailyCashClosing" class="btn btn-primary" {{ ($countOrders || $countFinances) ?: 'disabled'  }} >@lang('Checkout')</button>
					</div>
				</div>
			@endif
		</div>
	</div>

