        <div class="modal fade modal-search" id="modalFilters" tabindex="-1" role="dialog" aria-labelledby="modalFilters" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-blog-filter-light">
                    <div class="modal-body z-bigger d-flex justify-content-center align-items-center">
                        <div class="container-fluid">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="uil uil-multiply"></i>
                            </button>
                            <div class="row">
                                <div class="col-12 text-center ">
                                    @foreach($brands as $brand)                                 
                                        <a href="{{ route('frontend.shop.index', ['brandName' => (string)$brand->slug]) }}" id="brandName" class="btn btn-filter-tag light font-weight-800 mx-2 mx-xl-3 position-relative" data-filter=".category-4">{{ mb_strtolower($brand->name) }} <span class="btn-filter-icon bg-dark color-white">{{ $brand->products_count }}</span></a>
                                    @endforeach                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
