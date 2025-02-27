@extends('FsView::commons.sidebarLayout')

@section('sidebar')
    @include('FsView::app-center.sidebar')
@endsection

@section('content')
    <!--header-->
    <div class="row mb-3 border-bottom">
        <div class="col-lg-7">
            <h3>{{ __('FsLang::panel.sidebar_themes') }}</h3>
            <p class="text-secondary"><i class="bi bi-palette"></i> {{ __('FsLang::panel.sidebar_themes_intro') }}</p>
        </div>
        <div class="col-lg-5">
            <div class="input-group mt-2 mb-4 justify-content-lg-end">
                @if ($websiteEngineExists)
                    <label class="input-group-text"><i class="bi bi-laptop me-1"></i> {{ __('FsLang::panel.website_engine_status') }}</label>
                    <button class="btn {{ $params['website_engine_status'] ? 'btn-warning' : 'btn-secondary' }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {!! $params['website_engine_status'] ? '<i class="bi bi-play-circle me-1"></i>'.__('FsLang::panel.option_activate') : '<i class="bi bi-stop-circle me-1"></i>'.__('FsLang::panel.option_deactivate') !!}
                    </button>
                    <ul class="dropdown-menu">
                        <form action="{{ route('panel.update.item', ['itemKey' => 'website_engine_status']) }}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="itemValue" value="{{ $params['website_engine_status'] ? 'false' : 'true' }}">
                            <input type="hidden" name="itemType" value="boolean">
                            <button class="dropdown-item text-center" type="submit">{{ $params['website_engine_status'] ? __('FsLang::panel.button_deactivate') : __('FsLang::panel.button_activate') }}</button>
                        </form>

                        {{-- website-engine uninstall --}}
                        @if (! $params['website_engine_status'])
                            <li><hr class="dropdown-divider"></li>
                            <button class="dropdown-item text-danger text-center" type="button" data-bs-toggle="modal" data-bs-target="#engineUninstallConfirm">
                                <i class="bi bi-trash"></i> {{ __('FsLang::panel.button_uninstall') }}
                            </button>
                        @endif
                    </ul>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#engineModal">{{ __('FsLang::panel.website_engine_api_config') }}</button>
                @else
                    {{-- website-engine install --}}
                    <form method="post" action="{{ route('panel.app-center.website-engine', ['actionType' => 'install']) }}">
                        <button class="btn btn-warning px-3 position-relative ajax-progress-submit" type="submit" id="websiteEngineInstallSubmit">
                            <i class="bi bi-tools me-1"></i> {{ __('FsLang::panel.install_website_engine') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!--tip-->
    @if (! $websiteEngineExists)
        <div class="alert alert-danger" role="alert"><i class="bi bi-info-circle"></i> {{ __('FsLang::tips.website_engine_error') }}</div>
    @endif

    <div class="row border-bottom mb-5">
        {{-- desktop --}}
        <div class="col-lg-6 mb-3">
            <div class="input-group">
                <label class="input-group-text"><i class="bi bi-laptop me-1"></i> {{ __('FsLang::panel.website_engine_view_desktop') }}</label>
                <div class="form-control bg-white">{{ $desktopThemeName }}</div>
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __('FsLang::panel.button_modify') }}</button>
                <ul class="dropdown-menu">
                    @foreach ($themes as $theme)
                        <form action="{{ route('panel.update.item', ['itemKey' => 'website_engine_view_desktop']) }}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="itemValue" value="{{ $theme->fskey }}">
                            <button class="dropdown-item ps-3" type="submit">{{ $theme->name }}</button>
                        </form>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- mobile --}}
        <div class="col-lg-6 mb-3">
            <div class="input-group">
                <label class="input-group-text"><i class="bi bi-phone me-1"></i> {{ __('FsLang::panel.website_engine_view_mobile') }}</label>
                <div class="form-control bg-white">{{ $mobileThemeName }}</div>
                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __('FsLang::panel.button_modify') }}</button>
                <ul class="dropdown-menu">
                    @foreach ($themes as $theme)
                        <form action="{{ route('panel.update.item', ['itemKey' => 'website_engine_view_mobile']) }}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="itemValue" value="{{ $theme->fskey }}">
                            <button class="dropdown-item ps-3" type="submit">{{ $theme->name }}</button>
                        </form>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!--list-->
    <div class="row">
        @foreach ($themes as $theme)
            <div class="col-sm-6 col-md-4 col-xxl-3 mb-4">
                <div class="card">
                    <div class="position-relative">
                        <img src="/assets/{{ $theme->fskey }}/fresns.png" class="card-img-top">
                        @if ($theme->is_upgrade)
                            <div class="position-absolute top-0 start-100 translate-middle">
                                <a href="{{ route('panel.upgrades') }}"><span class="badge rounded-pill bg-danger">{{ __('FsLang::panel.new_version') }}</span></a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="text-nowrap overflow-hidden">
                            <a href="{{ $marketplaceUrl.'/detail/'.$theme->fskey }}" target="_blank" class="link-dark fresns-link">{{ $theme->name }}</a>
                            <span class="badge bg-secondary align-middle fs-9">{{ $theme->version }}</span>
                        </h5>
                        <p class="card-text text-height">{{ $theme->description }}</p>
                        <div>
                            @if ($theme->settings_path)
                                @if ($websiteEngineExists && $params['website_engine_status'] && Route::has('fresns.theme-admin.index'))
                                    <a href="{{ route('panel.app-center.theme.functions', ['url' => route('fresns.theme-admin.index', ['fskey' => $theme->fskey])]) }}" class="btn btn-primary btn-sm px-4">{{ __('FsLang::panel.button_setting') }}</a>
                                @else
                                    <div class="d-inline text-bg-primary rounded opacity-50 fs-7 px-3 py-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __('FsLang::panel.website_engine_status') }}: {{ $websiteEngineExists ? __('FsLang::panel.option_deactivate') : __('FsLang::tips.website_engine_error') }}">
                                        {{ __('FsLang::panel.button_setting') }}
                                    </div>
                                @endif
                            @endif
                            <button type="button" class="btn btn-link btn-sm ms-2 text-danger fresns-link"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteTheme"
                                data-fskey="{{ $theme->fskey }}"
                                data-name="{{ $theme->name }}">
                                {{ __('FsLang::panel.button_uninstall') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-footer fs-8">{{ __('FsLang::panel.author') }}: <a href="{{ $theme->author_link }}" target="_blank" class="link-info fresns-link">{{ $theme->author }}</a></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- delete theme -->
    <div class="modal fade" id="deleteTheme" tabindex="-1" aria-labelledby="deleteTheme" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-palette"></i> <span class="app-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('panel.theme.uninstall') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <input type="hidden" name="fskey" value="">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="delete_data" id="uninstallData">
                            <label class="form-check-label" for="uninstallData">{{ __('FsLang::panel.option_uninstall_theme_data') }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">{{ __('FsLang::panel.button_confirm_uninstall') }}</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('FsLang::panel.button_cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- engine config modal -->
    <div class="modal fade" id="engineModal" tabindex="-1" aria-labelledby="engineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="engineModalLabel">{{ __('FsLang::panel.website_engine_config') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('panel.client.engine.update') }}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body" id="accordionApiType">
                        <!--api_type-->
                        <div class="input-group mb-3">
                            <label class="input-group-text">{{ __('FsLang::panel.website_engine_api_type') }}</label>
                            <div class="form-control bg-white">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="website_engine_api_type" id="api_local" value="local" data-bs-toggle="collapse" data-bs-target=".local_key_setting:not(.show)" aria-expanded="true" aria-controls="local_key_setting" @if(($params['website_engine_api_type'] ?? '') == 'local') checked @endif>
                                    <label class="form-check-label" for="api_local">{{ __('FsLang::panel.option_local') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="website_engine_api_type" id="api_remote" value="remote" data-bs-toggle="collapse" data-bs-target=".remote_key_setting:not(.show)" aria-expanded="false" aria-controls="remote_key_setting" @if(($params['website_engine_api_type'] ?? '') == 'remote') checked @endif>
                                    <label class="form-check-label" for="api_remote">{{ __('FsLang::panel.option_remote') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('FsLang::panel.table_platform') }}</span>
                            <select class="form-select">
                                @foreach ($params['platforms'] as $platform)
                                    <option value="{{ $platform['id'] }}" @if ($platform['id'] != 4) disabled @else selected @endif>{{ $platform['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ __('FsLang::panel.table_type') }}</span>
                            <div class="form-control bg-white">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="1" id="fresns_key" checked>
                                    <label class="form-check-label" for="fresns_key">{{ __('FsLang::panel.key_option_main_api') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="2" id="admin_key" disabled>
                                    <label class="form-check-label" for="admin_key">{{ __('FsLang::panel.key_option_manage_api') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="3" id="plugin_key" disabled>
                                    <label class="form-check-label" for="plugin_key">{{ __('FsLang::panel.key_option_plugin_api') }}</label>
                                </div>
                            </div>
                        </div>
                        <!--api_type config-->
                        <!--api_local-->
                        <div class="collapse local_key_setting {{ ($params['website_engine_api_type'] ?? 'local') == 'local' ? 'show' : '' }}" aria-labelledby="api_local" data-bs-parent="#accordionApiType">
                            <div class="input-group mb-2">
                                <label class="input-group-text">{{ __('FsLang::panel.website_engine_key_id') }}</label>
                                <select class="form-select" name="website_engine_key_id">
                                    <option value="" {{ !($params['website_engine_key_id'] ?? '') ? 'selected' : '' }}>{{ __('FsLang::panel.option_not_set') }}</option>
                                    @foreach ($keys as $key)
                                        <option value="{{ $key->id }}" {{ ($params['website_engine_key_id'] ?? '') == $key->id ? 'selected' : '' }}>{{ $key->app_id }} - {{ $key->name }}</option>
                                    @endforeach
                                </select>
                                <a class="btn btn-outline-secondary" href="{{ route('panel.keys.index') }}" target="_blank" role="button">{{ __('FsLang::panel.button_view') }}</a>
                            </div>
                        </div>
                        <!--api_remote-->
                        <div class="collapse remote_key_setting {{ ($params['website_engine_api_type'] ?? 'local') == 'remote' ? 'show' : '' }}" aria-labelledby="api_remote" data-bs-parent="#accordionApiType">
                            <div class="input-group mb-3">
                                <label class="input-group-text">API Host</label>
                                <input type="url" class="form-control" name="website_engine_api_host" id="website_engine_api_host" value="{{ $params['website_engine_api_host'] ?? '' }}" placeholder="https://">
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text">API ID</label>
                                <input type="text" class="form-control" name="website_engine_api_app_id" id="website_engine_api_app_id" value="{{ $params['website_engine_api_app_id'] ?? '' }}">
                            </div>
                            <div class="input-group mb-2">
                                <label class="input-group-text">API Key</label>
                                <input type="text" class="form-control" name="website_engine_api_app_key" id="website_engine_api_app_key" value="{{ $params['website_engine_api_app_key'] ?? '' }}">
                            </div>
                        </div>
                        <!--api_type config end-->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('FsLang::panel.button_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- website-engine install artisan output modal -->
    <div class="modal fade fresns-modal" id="websiteEngineInstallStepModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="websiteEngineInstallStepModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tools"></i> {{ __('FsLang::panel.install_website_engine') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>
                </div>
                <div class="modal-body">
                    <pre class="form-control" id="websiteEngineInstall_artisan_output">{{ __('FsLang::tips.install_in_progress') }}</pre>

                    <!--progress bar-->
                    <div class="mt-2">
                        <div class="ajax-progress progress d-none" id="install-progress"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="reloadPage()">{{ __('FsLang::panel.button_close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- website-engine uninstall artisan output modal -->
    <div class="modal fade fresns-modal" id="websiteEngineUninstallStepModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="websiteEngineInstallStepModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tools"></i> {{ __('FsLang::panel.button_uninstall') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>
                </div>
                <div class="modal-body">
                    <pre class="form-control" id="websiteEngineUninstall_artisan_output">{{ __('FsLang::tips.uninstall_in_progress') }}</pre>

                    <!--progress bar-->
                    <div class="mt-2">
                        <div class="ajax-progress progress d-none" id="install-progress"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="reloadPage()">{{ __('FsLang::panel.button_close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- engine uninstall modal -->
    <div class="modal fade" id="engineUninstallConfirm" tabindex="-1" aria-labelledby="uninstall" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="{{ route('panel.app-center.website-engine', ['actionType' => 'uninstall']) }}" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('FsLang::panel.button_uninstall') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('FsLang::panel.button_cancel') }}</button>
                        <button type="button" class="btn btn-danger ajax-progress-submit" id="websiteEngineUninstallSubmit">{{ __('FsLang::panel.button_confirm_uninstall') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#websiteEngineInstallSubmit').click(function (e) {
            e.preventDefault();

            $('#websiteEngineInstallStepModal').modal('toggle');
            $(this).parent('form').submit(function (e) {
                e.preventDefault();
            });

            $.ajax({
                method: $(this).parent('form').attr('method'), // post form
                url: $(this).parent('form').attr('action'),
                // data: new FormData(document.querySelector('xxx')),
                contentType: false,
                processData: false,
                success: function (response) {
                    progressDown && progressDown()
                    var ansi_up = new AnsiUp;
                    var html = ansi_up.ansi_to_html(response);

                    console.log('install response', html)
                    $('#websiteEngineInstall_artisan_output').html(html || trans('tips.installSuccess')) //FsLang
                },
                error: function (response) {
                    progressExit && progressExit();

                    var errorMessage = response && response.responseJSON && response.responseJSON.message ? response.responseJSON.message : "Unknown error";
                    $('#websiteEngineInstall_artisan_output').html(errorMessage + "<br><br>" + trans('tips.installFailure'));

                    window.tips(errorMessage);
                },
            });
        });

        $('#websiteEngineUninstallSubmit').click(function (e) {
            e.preventDefault();

            $('#engineUninstallConfirm').modal('toggle');
            $('#websiteEngineUninstallStepModal').modal('toggle');
            $('#engineUninstallConfirm form').submit(function (e) {
                e.preventDefault();
            });

            $.ajax({
                method: $('#engineUninstallConfirm form').attr('method'), // post form
                url: $('#engineUninstallConfirm form').attr('action'),
                // data: new FormData(document.querySelector('xxx')),
                contentType: false,
                processData: false,
                success: function (response) {
                    progressDown && progressDown()
                    var ansi_up = new AnsiUp;
                    var html = ansi_up.ansi_to_html(response);

                    console.log('uninstall response', html)
                    $('#websiteEngineUninstall_artisan_output').html(html || trans('tips.installSuccess')) //FsLang
                },
                error: function (response) {
                    progressExit && progressExit();

                    var errorMessage = response && response.responseJSON && response.responseJSON.message ? response.responseJSON.message : "Unknown error";
                    $('#websiteEngineUninstall_artisan_output').html(errorMessage + "<br><br>" + trans('tips.installFailure'));

                    window.tips(errorMessage);
                },
            });
        });
    </script>
@endpush
