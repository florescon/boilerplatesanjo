<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                          <button class="btn btn-danger" onclick="window.close();">
                            @lang('Close Tab') <i class="cil-x"></i>
                          </button>
                        </div>

                        <h4 class="card-title">@lang('Threads')</h4>
                        <p class="card-description"> @lang('File') No. #PCH{{ $document->id }}. <em class="text-primary">{{ $document->title }}</em></p>
                        
                        <div class="row">
                            <div class="col">
                                <img class="card-img-top" src="{{ asset('/storage/' . $document->image) }}" alt="Card image cap">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm">
                                <x-utils.virtual-select 
                                  wire:model="selected_threads"
                                  :options="[
                                      'options' => collect($threads)->map(function($thread) {
                                          return [
                                              'label' => $thread->name.' '.$thread->code,
                                              'value' => $thread->id
                                          ];
                                      })->toArray(),
                                     'selectedValue' => [],
                                     'multiple' => true,
                                     'showValueAsTags' => true,
                                  ]"
                                />
                            </div>

                            <div class="col-sm">
                                @if($selected_threads)
                                    <a wire:click="save">@lang('Save')</a>
                                @endif
                            </div>
                        </div>

                        @if($document->doc_threads->count())
                            <p class="mt-4"><em>@lang('Order by name')</em></p>
                            <p class="mt-2"><strong>@lang('Threads'):</strong><em> {{ $document->doc_threads->count() }}</em></p>

                            <ul class="list-group">
                                @foreach($document->doc_threads->sortBy(['thread.name', 'asc']) as $getThread)
                                  <li class="list-group-item list-group-item-action flex-column align-items-start ">
                                    <div class="d-flex w-100 justify-content-between">
                                      <h5 class="mb-1">{{ $getThread->thread->code }}</h5>
                                      <h3 class="text-danger" wire:click="removeThead({{ $getThread->id }})"><i class="cil-x"></i></h3>
                                    </div>
                                    <p class="mb-1"> {{ $getThread->thread->code }}</p>
                                    <small><strong>@lang('Vendor')</strong> {{ $getThread->thread->vendor->name }}</small>
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
@endpush
