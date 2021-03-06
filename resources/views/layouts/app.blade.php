@include('includes.head')

<main class="container card {{ Route::current() ? str_replace('.', '-', Route::current()->getName()) : 'error' }}"
      role="main">
    @if (Route::current()->getName() === 'user.attribution')
        @php include_once public_path('/images/undraw_open_source_1qxw.svg') @endphp
    @endif
    <section class="container-fluid px-md-0 pb-2 overflow-y-scroll">
        @yield('breadcrumbs')
        @if (isset($title) && !Route::current()->named('user.login'))
            <h3 class="title">{{ $title ?? '' }}</h3>
        @endif
        @yield('content')
    </section>
</main>

@if (session()->has('message'))
    @component('includes.alert')
        @slot('level'){{ session()->get('message')['level'] }}@endslot
        @slot('heading'){{ session()->get('message')['heading'] }}@endslot
        @slot('body'){{ session()->get('message')['body'] ?? '' }}@endslot
    @endcomponent
@endif

@include('includes.footer')
