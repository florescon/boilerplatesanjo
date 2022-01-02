@extends('backend.layouts.app')

@section('title', __('Finances panel'))

@section('breadcrumb-links')
    @include('backend.store.includes.breadcrumb-links-finances')
@endsection

@section('content')

    <livewire:backend.store.finance-table />

    <livewire:backend.store.finance.create-finance />
    <livewire:backend.store.finance.edit-finance />

@endsection


@push('after-scripts')

    <script type="text/javascript">
      Livewire.on("financeStore", () => {
          $("#createFinance").modal("hide");
      });
    </script>

    <script type="text/javascript">
      Livewire.on("financeUpdate", () => {
          $("#editFinance").modal("hide");
      });
    </script>

@endpush
