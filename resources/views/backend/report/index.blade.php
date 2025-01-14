@extends('backend.layouts.app')

@section('title', __('Reports'))

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('/css_custom/pipeline.css') }}">
@endpush

@section('content')
    <x-backend.card>
        <x-slot name="body">

            <livewire:backend.report.report-table />

            <br><br>
            @if(!request()->details)
            <article class="bg-secondary mb-3">  
                <div class="card-body text-center">
                    <h4 class="text-white"> Información detallada acerca de los Reportes </h4>
                    <p class="h5 text-white"> Dar click en el siguiente botón para ver</p>   <br>
                    <p>
                        <a class="btn btn-warning" href="{{ route('admin.report.index') }}?details=true"> @lang('Reports')  
                        <i class="fa fa-window-restore "></i></a>
                    </p>
                </div>
                <br><br>
            </article>
            @else
            <article class="bg-secondary mb-3">  
                <div class="card-body text-center">
                    <p>
                        <a class="btn btn-warning" href="{{ route('admin.report.index') }}"> @lang('Back')  
                        <i class="fa fa-arrow-left "></i></a>
                    </p>
                </div>
                <br><br>
            </article>
            @endif

    	</x-slot>
    </x-backend.card>
@endsection
