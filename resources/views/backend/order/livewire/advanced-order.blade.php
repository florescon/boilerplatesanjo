<x-backend.card>

    <x-slot name="header">
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.order.edit', $order->id)" icon="fa fa-chevron-left" :text="__('Back')" />
    </x-slot>
    <x-slot name="body">

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">

                    <div class="ibox-content m-b-sm border-bottom">
                        <div class="p-xs">
                            <div class="pull-left m-r-md">
                                <i class="fa fa-globe text-navy mid-icon"></i>
                            </div>
                            <h2 class="mt-2"> &nbsp;Bienvenido a opciones avanzadas</h2> 

                            <h4>
                                &nbsp;{!! $order->type_order !!}
                                Folio #{{ $order->id }}, @lang('Order track'): {{ $order->slug }}
                      <a href="{{ route('frontend.track.show', $order->slug) }}" target=”_blank”>
                        <span class="badge badge-primary"> 
                          <i class="cil-external-link"></i>
                        </span>
                      </a>

                            </h4>
                            <span> &nbsp; {{ $order->comment }} </span>
                        </div>
                    </div>

                    <div class="ibox-content forum-container">

                        <div class="forum-title">
                            <div class="pull-right forum-desc">
                                {{-- <samll>Total posts: 17,800,600</samll> --}}
                            </div>
                            <h3>@lang('Feedstock')</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    @if(!$order->isDeletedFeedstock())
                                        @if($order->materials_order()->exists() && !$result)
                                            <x-utils.form-button
                                                :action="route('admin.order.delete-consumption', $order->id)"
                                                name="confirm-item"
                                                button-class="forum-item-title"
                                                {{-- permission="admin.access.user.clear-session" --}}
                                            >
                                               &nbsp; @lang('Delete consumption') &nbsp;
                                            </x-utils.form-button>

                                            <div class="forum-sub-title mt-2"> Se eliminará el consumo. Aplicable sólo una vez a esta orden. </div>
                                        @else
                                            <div class="forum-sub-title mt-2"> Límite de tiempo vencido para eliminar consumo o inexistente </div>
                                        @endif
                                    @else
                                        <div class="forum-sub-title mt-2">Se eliminó el consumo {{ $order->feedstock_changed_at }} </div>
                                    @endif
                                </div>
                                @if(!$order->isDeletedFeedstock())
                                    <div class="col-md-3 forum-info">
                                        <span class="views-number">
                                            7 @lang('Days')
                                        </span>
                                        <div>
                                            <small>@lang('Limit')</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="forum-title">
                            <div class="pull-right forum-desc">
                                {{-- <samll>Total posts: 17,800,600</samll> --}}
                            </div>
                            <h3>@lang('Other options')</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    @if(!$order->isUserOrDepartamentReasigned())
                                        @if(!$result && !$order->suborders()->exists())
                                            {{-- <x-utils.form-button
                                                :action="route('admin.order.reasign-user-departament', $order->id)"
                                                name="confirm-item"
                                                button-class="forum-item-title"
                                                permission="admin.access.user.clear-session"
                                            >
                                                &nbsp; Reasignar usuario/departamento &nbsp;
                                            </x-utils.form-button> --}}

                                              <x-utils.link
                                                icon="c-icon cil-plus"
                                                class="forum-item-title"
                                                data-toggle="modal" 
                                                wire:click="$emitTo('backend.order.reasign-user', 'createmodal', {{ $order_id }})" 
                                                data-target="#reasignUser"
                                                :text="__('Reasign user')"
                                              />

                                              <x-utils.link
                                                icon="c-icon cil-plus"
                                                class="forum-item-title"
                                                data-toggle="modal" 
                                                wire:click="$emitTo('backend.order.reasign-departament', 'departamentmodal', {{ $order_id }})" 
                                                data-target="#reasignDepartament"
                                                :text="__('Reasign departament')"
                                              />

                                            <div class="forum-sub-title mt-2">Toda la orden se reasignara al usuario o departamento. No aplica si la orden tiene subordenes definidas. Aplicable sólo una vez a esta {{ $order->type_order_clear }}. </div>
                                        @else
                                            <div class="forum-sub-title mt-2"> Límite de tiempo vencido para reasignar usuario/departamento o tiene subordenes definidas. </div>
                                        @endif                                        
                                    @else
                                        <div class="forum-sub-title mt-2">Reasignado {{ $order->user_departament_changed_at }} </div>
                                    @endif
                                </div>
                                @if(!$order->isUserOrDepartamentReasigned())
                                    <div class="col-md-3 forum-info">
                                        <span class="views-number">
                                            7 @lang('Days')
                                        </span>
                                        <div>
                                            <small>@lang('Limit')</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <livewire:backend.order.reasign-user />

        <livewire:backend.order.reasign-departament />

    </x-slot>

</x-backend.card>
