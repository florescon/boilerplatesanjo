<x-utils.modal id="sendFinalProduct" tform="send">
  <x-slot name="title">
    <em>@lang('Send to finished product confirm')</em>
  </x-slot>

  <x-slot name="content">
    <h1 class="text-warning"><i class="cil-warning"></i></h1>
    <h4>@lang('Are you sure want to send?')</h4>
  </x-slot>

  <x-slot name="footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      <button type="submit" class="btn btn-primary">@lang('Yes, send')</button>
  </x-slot>
</x-utils.modal>