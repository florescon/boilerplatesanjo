@if (session()->has('message-success'))
    <div class="alert alert-success" role="alert">
        {{ session('message-success') }}
    </div>
@endif

@if (session()->has('message-danger'))
    <div class="alert alert-danger">
        {{ session('message-danger') }}
    </div>
@endif

@if (session()->has('message-info'))
    <div class="alert alert-info">
        {{ session('message-info') }}
    </div>
@endif

