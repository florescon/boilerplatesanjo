<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-9 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                          <a class="btn btn-outline-primary btn-sm" type="button" target="_blank" href="{{ route('admin.document.print', $document->id ) }}">
                            @lang('File') <i class="fas fa-print m-1"></i>
                          </a>

                          <a type="button" class="btn btn-danger btn-sm text-white" onclick="window.close();">
                            @lang('Close Tab') <i class="cil-x"></i>
                          </a>
                        </div>

                        <h4 class="card-title">@lang('Threads') 🧵</h4>
                        <p class="card-description"> @lang('File') No. #PCH{{ $document->id }}. <em class="text-primary">{{ $document->title }}</em></p>
                        
                        <div class="row justify-content-md-center">
                            <div class="col-6">
                                <img class="card-img-top" src="{{ asset('/storage/' . $document->image) }}" alt="Card image cap">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm" wire:ignore>
                              <select id="materialmultiple" multiple="multiple" class="custom-select"  aria-hidden="true" required>
                              </select>
                            </div>

                            <div class="col-sm">
                                @if($material_id)
                                    <h3><a wire:click="save" class="text-primary">@lang('Save')</a></h3>
                                @endif
                            </div>
                        </div>

                        @if($document->doc_threads->count())
                            <p class="mt-4"><em>@lang('Order by name')</em></p>
                            <p class="mt-2"><strong>@lang('Threads'):</strong><em> {{ $document->doc_threads->count() }}</em></p>

                            <ul class="list-group">
                                @foreach($document->doc_threads->sortBy(['material.name', 'asc']) as $getThread)
                                  <li class="list-group-item list-group-item-action flex-column align-items-start ">
                                    <div class="d-flex w-100 justify-content-between">
                                      <h5 class="mb-1">{!! optional($getThread->material)->full_name !!}</h5>
                                      <h3 class="text-danger" wire:click="removeThead({{ $getThread->id }})"><i class="cil-x"></i></h3>
                                    </div>
                                    <p class="mb-1"> {{ optional($getThread->material)->part_number }}</p>
                                    @if($getThread->material_id)
                                        <small>
                                            <strong>@lang('Vendor')</strong> 
                                            {!! optional($getThread->material)->vendor->short_name_or_name !!}
                                        </small>
                                    @endif
                                  </li>
                                @endforeach
                            </ul>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        window.addEventListener('closeBrowserTab', event => {
            window.close();
        });
    </script>

  <script>
    $(document).ready(function() {
      $('#materialmultiple').select2({
        closeOnSelect: false,
        placeholder: '@lang("Choose feedstocks")',
        width: 'resolve',
        theme: 'bootstrap4',
        allowClear: true,
        multiple: true,
        ajax: {
              url: '{{ route('admin.material.selectthread') }}',
              data: function (params) {
                  return {
                      search: params.term,
                      page: params.page || 1
                  };
              },
              dataType: 'json',
              processResults: function (data) {
                  data.page = data.page || 1;

                  // Ordenar los elementos primero por item.color.name y luego por item.name
                  data.items.sort(function(a, b) {
                    if (a.color && b.color && a.color.name && b.color.name) {
                      if (a.color.name < b.color.name) return -1;
                      if (a.color.name > b.color.name) return 1;
                    }
                    if (a.name < b.name) return -1;
                    if (a.name > b.name) return 1;
                    return 0;
                  });

                  return {
                      results: data.items.map(function (item) {
                          return {
                            id: item.id,
                            text:  item.part_number.fixed() + ' ' +item.name + ' ' + (item.unit_id ? item.unit.name.sup() : '') + (item.color_id  ?  '<br> Color: ' + item.color.name.bold()  : '')  + (item.size_id  ?  '<br> Talla: ' + item.size.name.bold()  : '')

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
          },
          escapeMarkup: function(m) { return m; }

        });

        $('#materialmultiple').on('change', function (e) {
          var data = $('#materialmultiple').select2("val");
          @this.set('material_id', data);
        });

    });
  </script>

  <script type="text/javascript">
    Livewire.on("materialReset", () => {
      $('#materialmultiple').val([]).trigger("change");
    });
  </script>

@endpush
