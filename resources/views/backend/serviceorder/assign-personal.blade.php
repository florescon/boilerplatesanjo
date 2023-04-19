@push('after-styles')
    <link rel="stylesheet" href="{{ asset('css_custom/search-product.css') }}">
@endpush

<x-utils.modal id="assignPersonal">
  <x-slot name="title">
    @lang('Assign personal')
  </x-slot>

  <x-slot name="content">
    <input type="hidden" wire:model="selected_id">
    <input 
      wire:model="query" 
      type="text" 
      class="input-search mt-2 mb-4"
      placeholder="{{ __('Search') }}..."
      wire:keydown.escape="reset_search"
      wire:keydown.ArrowUp="decrementHighlight"
      wire:keydown.ArrowDown="incrementHighlight"
      wire:keydown.enter="dropdown"
    />

    @if(!empty($query))
      @if(!empty($users))
        <div class="card mt-2 border-0">
          <div class="card-body">
          <ul class="list-unstyled">
            @foreach($users as $i => $user)
              <li class="media" wire:click="selectUser({{ $user['id'] }})">
                <div class="c-avatar">
                  <img class="c-avatar-img mr-3" src="{{ $user['avatar'] }}" alt="{{ $user['email'] ?? '' }}">
                </div>
                <div class="media-body">
                  <h5 class="mt-0 mb-1 ">{{ $user['name'] }}</h5>
                  <h6>{{ $user['email'] }}</h6>
                  <h6>{{ $user['customer']['short_name'] ?? '' }} {{ $user['customer']['phone'] ?? '' }}
                  </h6>
                  <h6>
                    @if($user['last_login_at'])
                      @lang('Last Login At'): {{ $user['last_login_at'] }}
                    @else
                        @lang('N/A')
                    @endif
                  </h6>
                </div>
              </li>
            @endforeach
          </ul>
          </div>
        </div>
      @endif
    @endif
  </x-slot>

  <x-slot name="footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
  </x-slot>
</x-utils.modal>
