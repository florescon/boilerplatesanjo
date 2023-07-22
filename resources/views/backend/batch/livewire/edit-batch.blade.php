@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="editBatch" tform="store">
  <x-slot name="title">
    @lang('Edit batch')
  </x-slot>

  <x-slot name="content">

  </x-slot>

  <x-slot name="footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
    <button type="submit" class="btn btn-primary">@lang('Save')</button>
  </x-slot>
</x-utils.modal>
