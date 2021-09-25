<div class="animated fadeIn">

  @if($status != 'deleted')
  <div class="row mb-4 justify-content-md-center ">
    <div class="col-8 ">
      <div class="input-group">
        <input wire:model.debounce.350ms="searchTerm" class=" font-weight-bold input-search bg-light text-wrap" type="text" placeholder="{{ __('Search') }}..." />
          <span class="border-input-search"></span>
      </div>
    </div>
      @if($searchTerm !== '')
      <div class="input-group-append">
        <button type="button" wire:click="clear" class="close" aria-label="Close">
          <span aria-hidden="true"> &nbsp; &times; &nbsp;</span>
        </button>

      </div>
      @endif
  </div>
  @endif

  <div class="row">
    @if($pages->count())
      @foreach($pages as $page)
        <div class="col-sm-12 col-xl-6">
          <div class="card shadow-lg" style="{{ $page->active_background }}">
            <div class="card-header">
              <i class="fa fa-clock"></i> {{ $page->date_diff_for_humans }}
              <div class="card-header-actions">
                <a class="card-header-action" href="#" wire:click="changeActive({{ $page->id }})">
                  <small class="text-muted">{!! $page->is_active_page !!}</small>
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="jumbotron">
                <h1 class="display-3">{{ Str::limit($page->title, 20) }}</h1>
                <p class="lead">{!! Str::limit($page->content, 200) !!}</p>
                <hr class="my-4" />
                <p>
                  {!! $page->inactive_page !!}
                </p>
                <p>@lang('Updated at'): {{ $page->updated_at }}</p>
                <p>@lang('Created at'): {{ $page->created_at }}</p>
                <p class="lead">
                  <a class="btn btn-primary btn-lg" href="#" role="button">@lang('Show details')</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </div>

  <div class="row mt-4">
    <div class="col">
        @if($pages->count())
        <div class="row">
          <div class="col">
            <nav>
              {{ $pages->onEachSide(1)->links() }}
            </nav>
          </div>
              <div class="col-sm-3 text-muted text-right">
                Mostrando {{ $pages->firstItem() }} - {{ $pages->lastItem() }} de {{ $pages->total() }} resultados
              </div>
        </div>

        @else
          @lang('No search results') 
          @if($searchTerm)
            "{{ $searchTerm }}" 
          @endif

          @if($page > 1)
            {{ __('in the page').' '.$page }}
          @endif
        @endif

    </div>
  </div>

</div>
