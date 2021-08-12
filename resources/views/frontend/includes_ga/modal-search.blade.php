        <div class="modal fade modal-search" id="modalSearch" tabindex="-1" role="dialog" aria-labelledby="modalSearch" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-dark-blue">
                    <div class="modal-body z-bigger d-flex justify-content-center align-items-center">
                        <div class="container-fluid">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="uil uil-multiply"></i>
                            </button>
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <form action="{{ route('frontend.shop.index') }}" method="get">
                                        <div class="form-group">
                                            <input type="text" name="searchTermShop" id="searchTermShop" class="form-style search" placeholder="{{ __('Search') }}..." autocomplete="off">
                                        </div>
                                    </form>
                                    <p class="mb-0 mt-4 text-center color-gray-dark font-weight-500">@lang('Start typing & press "Enter" or "ESC" to close')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
