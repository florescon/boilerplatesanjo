@if(isset($errors) && $errors->any())
    <x-utils.alert-ga type="danger" class="header-message">
        @foreach($errors->all() as $error)
            {{ $error }}<br/>
        @endforeach
    </x-utils.alert-ga>
@endif

@if(session()->get('flash_success'))
    <x-utils.alert-ga type="success" class="header-message">
        {{ session()->get('flash_success') }}
    </x-utils.alert-ga>
@endif

@if(session()->get('flash_warning'))
    <x-utils.alert-ga type="warning" class="header-message">
        {{ session()->get('flash_warning') }}
    </x-utils.alert-ga>
@endif

@if(session()->get('flash_info') || session()->get('flash_message'))
    <x-utils.alert-ga type="info" class="header-message">
        {{ session()->get('flash_info') }}
    </x-utils.alert-ga>
@endif

@if(session()->get('flash_danger'))
    <x-utils.alert-ga type="danger" class="header-message">
        {{ session()->get('flash_danger') }}
    </x-utils.alert-ga>
@endif

@if(session()->get('status'))
    <x-utils.alert-ga type="success" class="header-message">
        {{ session()->get('status') }}
    </x-utils.alert-ga>
@endif

@if(session()->get('resent'))
    <x-utils.alert-ga type="success" class="header-message">
        @lang('A fresh verification link has been sent to your email address.')
    </x-utils.alert-ga>
@endif

@if(session()->get('verified'))
    <x-utils.alert-ga type="success" class="header-message">
        @lang('Thank you for verifying your e-mail address.')
    </x-utils.alert-ga>
@endif
