@include('includes.head')

<main class="container" role="main">
    <section class="container-fluid px-md-0">
        @if (session()->has('message'))
            <div class="alert alert-{{ session()->get('message')['level'] }}" role="alert">
                <h4 class="alert-heading">{{ session()->get('message')['heading'] }}</h4>
                {{ session()->get('message')['body'] }}
            </div>
        @endif
        <h1>{{ $title }}</h1>
        @yield('content')
    </section>
</main>

@include('includes.footer')
