@inject('model', '\App\Domains\Auth\Models\User')

@extends('backend.layouts.app')

@section('title', __('Update User'))

@section('content')
    <x-forms.patch :action="route('admin.auth.user.update', $user)">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update User')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.auth.user.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div x-data="{userType : '{{ $user->type }}'}">
                    @if (!$user->isMasterAdmin())
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label">@lang('Type')</label>

                            <div class="col-md-10">
                                <select name="type" class="form-control" required x-on:change="userType = $event.target.value">
                                    <option value="{{ $model::TYPE_USER }}" {{ $user->type === $model::TYPE_USER ? 'selected' : '' }}>@lang('User')</option>
                                    <option value="{{ $model::TYPE_ADMIN }}" {{ $user->type === $model::TYPE_ADMIN ? 'selected' : '' }}>@lang('Administrator')</option>
                                </select>
                            </div>
                        </div><!--form-group-->
                    @endif

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                        <div class="col-md-10">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $user->name }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->


                    <div class="form-group row">
                        <label for="short_name" class="col-md-2 col-form-label">@lang('Short name')</label>

                        <div class="col-md-10">
                            <input type="text" name="short_name" class="form-control" placeholder="{{ __('Short name') }}" value="{{ old('short_name') ?? optional($user->customer)->short_name }}" maxlength="100" required />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="phone" class="col-md-2 col-form-label">@lang('Phone')</label>

                        <div class="col-md-10">
                            <input type="text" name="phone" class="form-control" placeholder="{{ __('Phone') }}" value="{{ old('phone') ?? optional($user->customer)->phone }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="address" class="col-md-2 col-form-label">@lang('Address')</label>

                        <div class="col-md-10">
                            <input type="text" name="address" class="form-control" placeholder="{{ __('Address') }}" value="{{ old('address') ?? optional($user->customer)->address }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="rfc" class="col-md-2 col-form-label">@lang('RFC')</label>

                        <div class="col-md-10">
                            <input type="text" name="rfc" class="form-control" placeholder="{{ __('RFC') }}" value="{{ old('rfc') ?? optional($user->customer)->rfc }}" maxlength="100" />
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">@lang('Type price')</label>

                        <div class="col-md-10">
                            <select name="type_price" class="form-control">
                                <option value="{{ $model::PRICE_RETAIL }}" {{ optional($user->customer)->type_price === $model::PRICE_RETAIL ? 'selected' : '' }}>@lang('Retail price')</option>
                                <option value="{{ $model::PRICE_AVERAGE_WHOLESALE }}" {{ optional($user->customer)->type_price === $model::PRICE_AVERAGE_WHOLESALE ? 'selected' : '' }}>@lang('Average wholesale price')</option>
                                <option value="{{ $model::PRICE_WHOLESALE }}" {{ optional($user->customer)->type_price === $model::PRICE_WHOLESALE ? 'selected' : '' }}>@lang('Wholesale price')</option>
                                <option value="{{ $model::PRICE_SPECIAL }}" {{ optional($user->customer)->type_price === $model::PRICE_SPECIAL ? 'selected' : '' }}>@lang('Special price')</option>
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">@lang('E-mail Address')</label>

                        <div class="col-md-10">
                            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('E-mail Address') }}" value="{{ old('email') ?? $user->email }}" maxlength="255" required />
                        </div>
                    </div><!--form-group-->

                    @if (!$user->isMasterAdmin())
                        @include('backend.auth.includes.roles')

                        @if (!config('boilerplate.access.user.only_roles'))
                            @include('backend.auth.includes.permissions')
                        @endif
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update User')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
