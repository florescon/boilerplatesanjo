@extends('backend.layouts.app')

@section('title', __('Document'))

@section('content')

    <livewire:backend.document.document-table />

@endsection

@push('after-scripts')
    <script type="text/javascript">
      Livewire.on("documentStore", () => {
          $("#exampleModal").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("documentUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>
@endpush
