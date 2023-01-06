@if($model->last_status_order)
  @if($model->last_status_order->status->level === $model->last_status()->level)
    <div class="form-check">
      <input class="form-check-input"
        wire:model="hasStock"
        type="checkbox"
        name="toggle"
        value="" 
        id="toggle">
      <label class="form-check-label" for="toggle">
        <h5>@lang('Delivered to customer')</h5>
      </label>
    </div>
  @else
    <div>
      <p>Aún no disponible para entrega al cliente</p>
    </div>
  @endif
@else
  <div>
    <p>Ningún estado de orden definido</p>
  </div>
@endif