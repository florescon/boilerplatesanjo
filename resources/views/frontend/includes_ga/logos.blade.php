        <div class="section over-hide padding-top-bottom-80 bg-light-2" id="page-section">
            <div class="section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="section swiper-logos-2">
                                <div class="swiper-wrapper">
                                    @foreach($logos as $logo)                                    
                                        <div class="swiper-slide">
                                            <div class="section logos-wrap-2 bg-white border-4 section-shadow-blue text-center margin-auto">
                                                <img src="{{ asset('/storage/' . $logo->image) }}" alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
