<div class="container mt-2">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">@lang('In process')</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" target="_blank" href="{{ route('admin.information.status.printexportquantitiesall', [1, 1, 1]) }}">Exportar <span class="sr-only">(export)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" target="_blank" href="{{ route('admin.information.status.printexportquantitiesall', [1, 0, true]) }}">Exportar Agrupado <span class="sr-only">(export)</span></a>
      </li>
    </ul>
  </div>
</nav>
<br>

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
