@extends('backend.layouts.app')

@section('title', __('Daily cash closing panel'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/pos_custom.css') }}">
@endpush


@section('content')
<div class="pcoded-content">

	<livewire:backend.store.box.header-box />

	<div class="pcoded-inner-content">
		<div class="main-body">
			<div class="page-wrapper">
				<div class="alert alert-success text-center py-4" role="alert">
				  <a href="{{ route('admin.store.box.history') }}" class="alert-link">@lang('Go to daily cash closings')</a>
				</div>
				<div class="page-body">
					<div class="row">
						<livewire:backend.store.box.order-box />
						<livewire:backend.store.box.finance-box />
					</div>
				</div>
			</div>
			<div id="styleSelector"> </div>
		</div>
	</div>
</div>


<livewire:backend.store.pos.create-cash />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("cashStore", () => {
          $("#createCash").modal("hide");
      });
    </script>
@endpush
