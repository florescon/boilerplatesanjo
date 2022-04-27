@extends('backend.layouts.app')

@section('title', __('Create product'))

@section('content')

	    <livewire:backend.product.create-product />

@endsection

@push('after-scripts')

	<script src="https://unpkg.com/html5-qrcode"></script>

	<script>
		function onScanSuccess(decodedText, decodedResult) {
		    // Handle on success condition with the decoded text or result.
		    console.log(`Scan result: ${decodedText}`, decodedResult);
		}

		var html5QrcodeScanner = new Html5QrcodeScanner(
			"reader", { fps: 10, qrbox: 250 });
		html5QrcodeScanner.render(onScanSuccess);	
	</script>

@endpush