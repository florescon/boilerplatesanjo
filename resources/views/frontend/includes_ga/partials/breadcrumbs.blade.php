@if (Breadcrumbs::has() && !Route::is('frontend.index'))
    <nav id="breadcrumbs" aria-label="breadcrumb">
        <ol class="breadcrumb no-border">
            @foreach (Breadcrumbs::current() as $crumb)
                @if ($crumb->url() && !$loop->last)
                    <li class="breadcrumb-item">
                        <x-utils.link :href="$crumb->url()" :text="$crumb->title()" />
                    </li>
                @else
                    <li class="breadcrumb-item active font-weight-500" aria-current="page">
                        <span class="size-14">
                            {{ $crumb->title() }}
                        </span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif


{{-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb no-border">
        <li class="breadcrumb-item"><a href="#" class="link link-dark-primary size-14" data-hover="Home">Home</a></li>
        <li class="breadcrumb-item active font-weight-500" aria-current="page"><span class="size-14">Shop</span></li>
    </ol>
</nav> --}}              
