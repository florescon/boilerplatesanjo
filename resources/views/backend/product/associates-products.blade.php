@extends('backend.layouts.app')

@section('title', __('Associates'))

@push('after-styles')
  <link rel="stylesheet" href="{{ asset('/css_custom/associates.css')}}">
@endpush

@section('content')

<x-backend.card>

  <x-slot name="header">
    @lang('Associated products')
  </x-slot>

  <x-slot name="headerActions">
    <x-utils.link class="card-header-action" :href="route('admin.order.index')" :text="__('Back')" />
  </x-slot>
  <x-slot name="body">
    @if($associates->count())
    <div class="row ">
      <div class="col-12 col-sm-12 col-md-12" style="margin-top: 40px;">
        <div class="col-sm-12">
            <div class="row">
              <div class="col-6">
                <div class="c-callout c-callout-info"><small class="text-muted">@lang('Associated products')</small>
                  <div class="text-value-lg">{{ $model->count_products }}</div>
                </div>
              </div>

              <div class="col-6">
                <div class="c-callout c-callout-danger"><small class="text-muted">Porcentaje de asociados del total de variantes de productos</small>
                  <div class="text-value-lg">{{ round($model->total_percentage, 2) }}%</div>
                </div>
              </div>
            </div>

            <div class="progress-group">
              <div class="progress-group-header align-items-end">
                <svg class="c-icon progress-group-icon">
                  <use xlink:href="vendors/@coreui/icons/svg/brand.svg#cib-google"></use>
                </svg>
                <div>{{ $model->name }}</div>
                <div class="mfs-auto font-weight-bold mfe-2">{{ $model->count_products }}</div>
                <div class="text-muted small">({{ round($model->total_percentage, 2) }}%)</div>
              </div>
              <div class="progress-group-bars">
                <div class="progress progress-xs">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $model->total_percentage }}%" aria-valuenow="{{ $model->total_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
        </div>
        <hr class="mt-0">
          <div class="row container d-flex justify-content-center">
            <div class="col-lg-10 grid-margin stretch-card">
              <table class="table table-responsive-sm table-hover table-outline mb-0">
                  <thead class="thead-light">
                      <tr>
                          <th>@lang('Product')</th>
                          <th>@lang('Created at')</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($associates as $product)
                      <tr>
                          <td>
                              <div>{!! $product->full_name !!}</div>
                              <div class="small text-muted"><span>{{ $product->new_product }}</span> @lang('Registered product'): {{ $product->date_for_humans_special }}</div>
                          </td>
                          <td>
                              <div class="small text-muted">@lang('Associated')</div><strong>{{ $product->date_for_humans }}</strong>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              <div class="mt-4 a">
                  {{ $associates->links() }}
              </div>
            </div>
          </div>
      </div>
    </div>
    @else
        <div class="jumbotron">
            <h1 class="display-3">@lang('No associates!')</h1>
            <p class="lead">@lang('There are no associated products').</p>
            <hr class="my-4">
            <p class="lead"><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
        </div>
    @endif
  </x-slot>

  <x-slot name="footer">
    <footer class="blockquote-footer float-right">
      Mies Van der Rohe <cite title="Source Title">Less is more</cite>
    </footer>
  </x-slot>

</x-backend.card>
@endsection

@push('after-scripts')

@endpush