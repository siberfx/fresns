@extends('FsView::commons.layout')

@section('body')
    <main class="form-signin text-center">
        <form method="post" class="p-3" action="{{ route('panel.login') }}">
            @csrf
            <img class="mt-3 mb-2" src="/static/images/icon.png" alt="Fresns" width="72" height="72">
            <h2 class="mb-5">{{ __('FsLang::panel.fresns_panel') }}</h2>
            <h4 class="mb-3 fw-normal">{{ __('FsLang::panel.language') }}</h4>
            <select class="form-select mb-5 change-lang" aria-label=".form-select-lg example">
                @foreach ($langs as $code => $lang)
                    <option value="{{ $code }}" @if ($code == \App::getLocale()) selected @endif>{{ $lang }}</option>
                @endforeach
            </select>
            <h4 class="mb-3 fw-normal">{{ __('FsLang::panel.login') }}</h4>
            <div class="form-floating text-start">
                <input type="text" class="form-control rounded-bottom-0" name="accountName" value="{{ old('accountName') }}" required placeholder="name@example.com">
                <label for="account">{{ __('FsLang::panel.account') }}</label>
            </div>
            <div class="form-floating text-start">
                <input type="password" class="form-control rounded-top-0 border-top-0" name="password" required placeholder="Password">
                <label for="password">{{ __('FsLang::panel.password') }}</label>
            </div>
            <button type="submit" class="w-100 btn btn-lg btn-primary mt-4">{{ __('FsLang::panel.enter') }}</button>
            <p class="my-5 text-muted">&copy; {{ date('Y') }} Fresns</p>
        </form>
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
