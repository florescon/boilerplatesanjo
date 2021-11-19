@extends('backend.layouts.app')

@section('title', __('Daily cash closing'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('View daily cash closing') #{{ $box->id }}
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.store.box.history')" :text="__('Back')" />
        </x-slot>
        <x-slot name="body">
			<div class="card-group">
			  <livewire:backend.store.box.box-history-finance-show :cash="$box" />
			  <livewire:backend.store.box.box-history-order-show :cash="$box" />
			</div>
		</x-slot>
	</x-backend.card>
@endsection