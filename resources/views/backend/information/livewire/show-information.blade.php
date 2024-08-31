<div class="container mt-2">
  <div class="row">
    @foreach($statuses as $status)
      <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm border-{{ $status->batch ? 'primary' : ($status->supplier ? 'warning' : 'success') }}" style="background-color: #f8f9fa;">
          <div class="card-header content-center p-4" style="background: linear-gradient(58.24deg, #FFFFFF 0%, #F9F9FF 37.4%, #EBECF7 90%)">
            <strong>{{ ucfirst($status->name) }}</strong>
          </div>
          <div class="card-body row text-center">
            <div class="col">
              <div class="text-value-xl">{{ $status->getAllQuantitiesByStatusOpened() }}</div>
              <div class="text-uppercase text-muted small">@lang('Process')</div>
            </div>
            <div class="vr"></div>
            <div class="col">
              <div class="text-value-xl">{{ $status->getAllQuantitiesByStatusClosed() }}</div>
              <div class="text-uppercase text-muted small">@lang('Finished')</div>
            </div>
          </div>
          <div class="text-center p-3">
            @foreach($status->buttons_labels as $button)
              {!! $button !!}
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
