@extends('backend.layouts.app')

@section('title', __('Departament'))

@section('content')

    <livewire:backend.departament.departament-table />

@endsection

@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("departamentStore", () => {
          $("#exampleModal").modal("hide");
      });
    </script>


    <script type="text/javascript">
      Livewire.on("departamentUpdate", () => {
          $("#updateModal").modal("hide");
      });
    </script>

@endpush
