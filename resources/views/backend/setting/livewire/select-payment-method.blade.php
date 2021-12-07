<div class="form-group row" wire:ignore>
    @if(!$clear)
        <label for="paymentselect" class="col-sm-3 col-form-label">@lang('Payment method')</label>
    @endif
    <div class="{{ $clear ? 'col-sm-12 text-center' : 'col-sm-9' }}" >
		<select id="paymentselect" class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#paymentselect').select2({
          placeholder: '@lang("Choose payment method")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.payments.select') }}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.title
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250,
                dropdownautowidth: true
            }
          });
          $('#paymentselect').on('change', function (e) {
            var data = $('#paymentselect').select2("val");
            livewire.emit('selectPaymentMethod', data)
          });
      });
    </script>
@endpush