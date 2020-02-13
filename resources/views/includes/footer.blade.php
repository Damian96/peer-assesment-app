<footer class="container-fluid mt-5 py-2 bg-dark text-white">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 text-right">
            Powered by <a title="Laravel" target="_blank" href="https://laravel.com">Laravel Framework</a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 text-left">
            Timezone: {{ config('app.timezone') }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4">
            Designed by <a title="Damian96" target="_blank" href="https://github.com/Damian96">@Damian96</a>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 text-left text-center">
            <strong class="text-warning">Beta</strong> version
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 text-center">
            Copyright &copy; <?php echo date('Y'); ?> <strong class="text-primary"><a title="Damian96" target="_blank"
                                                                                      href="https://github.com/Damian96">@Damian96</a></strong>
        </div>
    </div>
</footer>

@yield('end_footer')

</body>
</html>
