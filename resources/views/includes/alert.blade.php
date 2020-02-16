<div class="alert alert-{{ $level }} hidden">
    <span class="btn btn-sm btn-secondary material-icons float-right alert-close">close_icon</span>
    <h4 class="alert-heading">{{ $heading }}</h4>
    <p class="my-0">
        {!! html_entity_decode(str_replace('\n', '<br>', $body)) ?? null !!}
    </p>
</div>
