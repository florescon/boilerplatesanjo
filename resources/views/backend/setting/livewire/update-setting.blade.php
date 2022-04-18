<div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('General setting')</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form wire:submit.prevent="updateSetting">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="siteName">@lang('Site phone')</label>
                                    <input wire:model.defer="state.site_phone" type="text" class="form-control" id="siteName" placeholder="{{__('Enter phone') }}">
                                    @error('state.site_phone') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="siteEmail">@lang('Site email')</label>
                                    <input wire:model.defer="state.site_email" type="text" class="form-control" id="siteEmail" placeholder="{{__('Enter email') }}">
                                    @error('state.site_email') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="siteTitle">@lang('Site address')</label>
                                    <textarea rows="4" wire:model.defer="state.site_address" type="text" class="form-control" id="siteTitle" placeholder="{{__('Enter address') }}"></textarea>
                                    @error('state.site_address') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Site whatsapp')</label>
                                    <input wire:model.defer="state.site_whatsapp" type="text" class="form-control" id="footerText" placeholder="{{__('Enter whatsapp') }}">
                                    @error('state.site_whatsapp') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Site facebook')</label>
                                    <input wire:model.defer="state.site_facebook" type="text" class="form-control" id="footerText" placeholder="{{__('Enter facebook') }}">
                                    @error('state.site_facebook') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Days of orders')</label>
                                    <input wire:model.defer="state.days_orders" type="text" class="form-control" id="footerText" placeholder="{{__('Enter days') }}">
                                    @error('state.days_orders') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('IVA') %</label>
                                    <input wire:model.defer="state.iva" type="text" class="form-control" id="footerText" placeholder="{{__('Enter IVA') }}">
                                    @error('state.iva') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Retail price percentage')</label>
                                    <input wire:model.defer="state.retail_price_percentage" type="text" class="form-control" id="footerText" placeholder="{{__('Retail price percentage') }}">
                                    @error('state.retail_price_percentage') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Average wholesale price percentage')</label>
                                    <input wire:model.defer="state.average_wholesale_price_percentage" type="text" class="form-control" id="footerText" placeholder="{{__('Average wholesale price percentage') }}">
                                    @error('state.average_wholesale_price_percentage') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="footerText">@lang('Wholesale price percentage')</label>
                                    <input wire:model.defer="state.wholesale_price_percentage" type="text" class="form-control" id="footerText" placeholder="{{__('Wholesale price percentage') }}">
                                    @error('state.wholesale_price_percentage') <span class="error" style="color: red;"><p>{{ $message }}</p></span> @enderror
                                </div>
                                <div class="form-group border rounded text-center">
                                    <label for="footerText">Redondear precios sugeridos a múltiplos de 5</label>
                                    <br>
                                    <label class="c-switch c-switch-primary">
                                      <input type="checkbox" class="c-switch-input" wire:model.defer="state.round">
                                      <span class="c-switch-slider"></span>
                                    </label>
                                </div>

                                {{-- <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="sidebarCollapse">
                                        <label class="custom-control-label" for="sidebarCollapse">Sidebar Collapse</label>
                                    </div> --}}
                                    <!-- <label for="sidebar_collapse">Sidebar Collapse</label><br>
                                    <input wire:model.defer="state.sidebar_collapse" type="checkbox" id="sidebar_collapse"> -->
                                {{-- </div> --}}
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-1"></i>@lang('Save changes')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('after-scripts')
    <script>
        $('#sidebarCollapse').on('change', function() {
            $('body').toggleClass('sidebar-collapse');
        })
    </script>
@endpush
