<div class="row">
  <div class="col-sm-9">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $name }}</h5>
        <p class="card-text">Entrega total de productos: {{ $user->total_quantities }}</p>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Total <i class="cil-mood-very-good"></i></h5>
        <p class="card-text">${{ $user->total_quantities_with_making }}</p>
      </div>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <ul class="list-group">
          @foreach($assignments as $assignment)
            <li class="list-group-item list-group-item-action flex-column align-items-start">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{!! $assignment->assignment->assignmentable->product->full_name !!}
                  <span class="badge badge-primary badge-pill">{{ $assignment->quantity }}</span>
                  @if($assignment->assignment->assignmentable->product->parent->price_making)
                   <i class="cil-x"></i>
                    ${{ $assignment->assignment->assignmentable->product->parent->price_making ?? null }}
                    =
                    <strong class="text-danger">${{ $assignment->assignment->assignmentable->product->parent->price_making * $assignment->quantity }}</strong>
                  @endif
                </h5>
                <small class="text-muted">{{ $assignment->created_at }}</small>
              </div>
              <p class="mb-1">@lang('Ticket'): #{{ $assignment->ticket_id }}</p>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

</div>
