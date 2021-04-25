@extends('backend.layouts.app')

@section('title', __('Color'))

@section('content')

    <livewire:backend.color-table />

@endsection


@push('after-scripts')

	<script type="text/javascript">
	  Livewire.on("colorStore", () => {
	      $("#exampleModal").modal("hide");
	  });
	</script>


	<script type="text/javascript">
	  Livewire.on("colorUpdate", () => {
	      $("#updateModal").modal("hide");
	  });
	</script>


@endpush
