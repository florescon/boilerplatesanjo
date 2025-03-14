<div class="form-group row" wire:ignore>
    @if(!$clear)
        <label for="userselect" class="col-sm-3 col-form-label">@lang('Personal')</label>
    @endif
    <div class="col-sm-9" >
		<select id="userselect"  class="custom-select" style="width: 100%;" aria-hidden="true">
		</select>
    </div>
</div><!--form-group-->

@push('after-scripts')
    <script>
      $(document).ready(function() {
        $('#userselect').select2({
          placeholder: '@lang("Choose internal user")',
          width: 'resolve',
          theme: 'bootstrap4',
          allowClear: true,
          ajax: {
                url: '{{ route('admin.users.onlyAdmins') }}',
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
                                text: item.name.toLowerCase().replace(/\b\w/g, function(letter) {
                                    return letter.toUpperCase();
                                })
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

          $('#userselect').on('change', function (e) {
            var data = $('#userselect').select2("val");
            livewire.emit('selectedCompanyItem', data)

          });

      });
    </script>
@endpush