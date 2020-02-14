<div class="alert alert-{{ $level }}">
    <h4 class="alert-heading">{{ $heading }}</h4>
    {!! str_replace('\n', '<br>', $body) ?? '' !!}
</div>
