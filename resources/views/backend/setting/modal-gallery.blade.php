<!-- Modal -->
<div class="modal fade bd-example-modal-lg"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('Gallery details')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">

        <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            Tamaño máximo de 1MB, límite de 5 imágenes. Para ser mostrado es necesario especificar el orden.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="row">
          <div class="col-md-12">
            <img src="{{ asset('/ga/img/modal/export.png') }}" class="img-fluid" alt="modal">
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
      </div>
    </div>
  </div>
</div>
