@extends('FsView::commons.layout')

@section('body')
    <header class="form-signin text-center">
        <img class="my-5" src="/static/images/logo.png" alt="Fresns" height="72">

        <h4 class="mb-3 fw-normal">{{ __('FsLang::panel.language') }}</h4>
        <select class="form-select mb-5 change-lang" aria-label=".form-select-lg example">
            @foreach ($langs as $code => $lang)
                <option value="{{ $code }}" @if ($code == \App::getLocale()) selected @endif>{{ $lang }}</option>
            @endforeach
        </select>
    </header>

    <main class="container">
        <div class="card mx-auto my-5" style="max-width:800px;">
            <div class="card-body p-5">
                <h3 class="card-title">{{ __('FsLang::tips.auth_empty_title') }}</h3>
                <p class="mt-3">{{ __('FsLang::tips.auth_empty_description') }}</p>
                <p class="mt-4 mb-0"><a class="btn btn-outline-primary btn-sm" href="{{ $siteUrl }}" role="button"><i class="bi bi-house-door"></i> {{ __('FsLang::panel.site_home') }}</a></p>
            </div>
        </div>

        <div class="text-center">
            <p class="my-5 text-muted">&copy; {{ date('Y') }} Fresns</p>
        </div>
    </main>
@endsection

@push('css')
    <style>
        body {
            background-image: url(/static/images/panel-bg.svg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
@endpush

@push('script')
    <script>
        $('.change-lang').change(function() {
            var lang = $(this).val();
            let url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            window.location.href = url.href;
        });
    </script>
@endpush
