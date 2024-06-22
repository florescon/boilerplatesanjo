@extends('backend.layouts.app')

@section('title', __('Show Information'))

@push('after-styles')

@endpush

@section('content')

	<livewire:backend.information.information-table :status="$status" />

@endsection

@push('after-scripts')
	<script type="text/javascript">
	    function redirect(goto) {
	      var conf = confirm("¿Redireccionar?");
	      if (conf && goto != '') {
	        window.location = goto;
	      }
	    }

	  var selectEl = document.getElementById('redirectSelect');

	  selectEl.onchange = function() {
	    if (this.value.startsWith('http')) {
	      var goto = this.value;
	      redirect(goto);
	    }
	  };

	</script>

    <script>
        Livewire.on('clear-personal', clear => {
            jQuery(document).ready(function () {
                $("#userselect").val('').trigger('change')
            });
        })
    </script>
@endpush