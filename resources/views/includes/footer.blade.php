<footer class="container-fluid py-2 bg-dark text-white">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4">
            Powered by <a title="Laravel" target="_blank" href="https://laravel.com">Laravel Framework</a>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 text-center">
            Timezone: <strong>{{ config('app.timezone') }}</strong>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <a href="{{ url('/attribution') }}">Attribution</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-6 text-center">
            <strong class="text-warning">Stable 1.0</strong> version
        </div>
        <div class="col-xs-12 col-sm-4 col-md-6 text-center">
            Copyright &copy; <?php echo date('Y'); ?> <strong class="text-primary"><a title="Damian96" target="_blank"
                                                                                      href="https://github.com/Damian96">@Damian96</a></strong>
        </div>
    </div>
</footer>

@yield('end_footer')

<script type="text/javascript" defer>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).tooltip();
    });
</script>

</body>
</html>
