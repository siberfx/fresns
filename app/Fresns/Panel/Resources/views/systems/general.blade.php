@extends('FsView::commons.sidebarLayout')

@section('sidebar')
    @include('FsView::systems.sidebar')
@endsection

@section('content')
    <!--header-->
    <div class="row mb-4 border-bottom">
        <div class="col-lg-7">
            <h3>{{ __('FsLang::panel.sidebar_general') }}</h3>
            <p class="text-secondary">{{ __('FsLang::panel.sidebar_general_intro') }}</p>
        </div>
        <div class="col-lg-5">
            <div class="input-group mt-2 mb-4 justify-content-lg-end">
                {{-- <a class="btn btn-outline-secondary" href="#" role="button">{{ __('FsLang::panel.button_support') }}</a> --}}
            </div>
        </div>
    </div>

    <!--config-->
    <form action="{{ route('panel.general.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row mb-4">
            <label for="site_url" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_url') }}:</label>
            <div class="col-lg-6"><input type="url" class="form-control" name="site_url" value="{{ $params['site_url'] }}" id="site_url" placeholder="https://"></div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.site_url_desc') }}</div>
        </div>

        <div class="row mb-4">
            <label for="site_name" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_name') }}:</label>
            <div class="col-lg-6">
                <button type="button" class="btn btn-outline-secondary btn-modal w-100 text-start" data-bs-toggle="modal" data-bs-target="#siteNameModal">{{ $defaultLangParams['site_name'] }}</button>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.site_name_desc') }}</div>
        </div>

        <div class="row mb-4">
            <label for="site_intro" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_intro') }}:</label>
            <div class="col-lg-6">
                <div class="input-group mb-2">
                    <span class="input-group-text">Description</span>
                    <button type="button" class="btn btn-outline-secondary form-control btn-modal text-start" data-bs-toggle="modal" data-bs-target="#siteDescModal">{{ $defaultLangParams['site_desc'] }}</button>
                </div>
                <div class="input-group">
                    <span class="input-group-text">Introduction</span>
                    <button type="button" class="btn btn-outline-secondary form-control btn-modal text-start" data-bs-toggle="modal" data-bs-target="#siteIntroModal">{{ Str::limit($defaultLangParams['site_intro'], 140) }}</button>
                </div>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.site_intro_desc') }}</div>
        </div>

        <div class="row mb-4">
            <label for="site_img" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_logo') }}:</label>
            <div class="col-lg-6">
                <div class="input-group mb-1">
                    <label class="input-group-text font-monospace" for="ICON">ICON</label>
                    <!--Options-->
                    <button class="btn btn-outline-secondary dropdown-toggle showSelectTypeName" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if ($configImageInfo['iconType'] == 'ID')
                            {{ __('FsLang::panel.button_image_upload') }}
                        @else
                            {{ __('FsLang::panel.button_image_input') }}
                        @endif
                    </button>
                    <ul class="dropdown-menu selectInputType">
                        <li data-name="inputFile"><a class="dropdown-item" href="#">{{ __('FsLang::panel.button_image_upload') }}</a></li>
                        <li data-name="inputUrl"><a class="dropdown-item" href="#">{{ __('FsLang::panel.button_image_input') }}</a></li>
                    </ul>
                    <!--Input-->
                    <input type="file" class="form-control inputFile" name="site_icon_file" accept=".png,.gif,.jpg,.jpeg,image/png,image/apng,image/vnd.mozilla.apng,image/gif,image/jpeg,image/pjpeg,image/jpeg,image/pjpeg" @if ($configImageInfo['iconType'] == 'URL') style="display:none;" @endif>
                    <input type="text" class="form-control inputUrl" name="site_icon_url" @if ($configImageInfo['iconType'] == 'ID') style="display:none;" @endif  @if ($configImageInfo['iconType'] == 'URL') value="{{ $params['site_icon'] }}" @endif>
                    <!--Hidden item-->
                    <input type="hidden" name="site_icon" value="{{ $params['site_icon'] }}">
                    <!--Preview-->
                    @if ($params['site_icon'])
                        <button class="btn btn-outline-secondary preview-image" type="button" data-url="{{ $configImageInfo['iconUrl'] }}">{{ __('FsLang::panel.button_view') }}</button>
                    @endif
                </div>
                <div class="input-group">
                    <label class="input-group-text font-monospace" for="LOGO">LOGO</label>
                    <!--Options-->
                    <button class="btn btn-outline-secondary dropdown-toggle showSelectTypeName" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if ($configImageInfo['logoType'] == 'ID')
                            {{ __('FsLang::panel.button_image_upload') }}
                        @else
                            {{ __('FsLang::panel.button_image_input') }}
                        @endif
                    </button>
                    <ul class="dropdown-menu selectInputType">
                        <li data-name="inputFile"><a class="dropdown-item" href="#">{{ __('FsLang::panel.button_image_upload') }}</a></li>
                        <li data-name="inputUrl"><a class="dropdown-item" href="#">{{ __('FsLang::panel.button_image_input') }}</a></li>
                    </ul>
                    <!--Input-->
                    <input type="file" class="form-control inputFile" name="site_logo_file" accept=".png,.gif,.jpg,.jpeg,image/png,image/apng,image/vnd.mozilla.apng,image/gif,image/jpeg,image/pjpeg,image/jpeg,image/pjpeg" @if ($configImageInfo['logoType'] == 'URL') style="display:none;" @endif>
                    <input type="text" class="form-control inputUrl" name="site_logo_url" @if ($configImageInfo['logoType'] == 'ID') style="display:none;" @endif  @if ($configImageInfo['logoType'] == 'URL') value="{{ $params['site_logo'] }}" @endif>
                    <!--Hidden item-->
                    <input type="hidden" name="site_logo" value="{{ $params['site_logo'] }}">
                    <!--Preview-->
                    @if ($params['site_logo'])
                        <button class="btn btn-outline-secondary preview-image" type="button" data-url="{{ $configImageInfo['logoUrl'] }}">{{ __('FsLang::panel.button_view') }}</button>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.site_logo_desc') }}</div>
        </div>

        <div class="row mb-4">
            <label for="site_copyright" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_copyright') }}:</label>
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text">&copy;</span>
                    <input type="text" class="form-control" id="site_copyright_name" placeholder="Fresns" name="site_copyright_name" value="{{ $params['site_copyright_name'] }}">
                    <input type="text" class="form-control" id="site_copyright_years" placeholder="2021-{{ date('Y') }}" name="site_copyright_years" value="{{ $params['site_copyright_years'] }}">
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <label for="site_email" class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_email') }}:</label>
            <div class="col-lg-6"><input type="email" class="form-control" id="site_email" name="site_email" value="{{ $params['site_email'] }}" placeholder="support@fresns.org"></div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.site_email_desc') }}</div>
        </div>

        <!--site_mode-->
        <div class="row">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.site_mode') }}:</label>
            <div class="col-lg-5 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="site_mode" id="site_mode_public" value="public" data-bs-toggle="collapse" data-bs-target=".private_setting.show" aria-expanded="false" aria-controls="private_setting" {{ ($params['site_mode'] == 'public') ? 'checked' : '' }}>
                    <label class="form-check-label" for="site_mode_public">{{ __('FsLang::panel.site_mode_public') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="site_mode" id="site_mode_private" value="private" data-bs-toggle="collapse" data-bs-target=".private_setting:not(.show)" aria-expanded="false" aria-controls="private_setting" {{ ($params['site_mode'] == 'private') ? 'checked' : '' }}>
                    <label class="form-check-label" for="site_mode_private">{{ __('FsLang::panel.site_mode_private') }}</label>
                </div>
            </div>
        </div>
        <!--site_mode_private Config-->
        <div class="row mb-4">
            <label class="col-lg-2 col-form-label text-lg-end"></label>
            <div class="col-lg-5">
                <div class="collapse private_setting {{ ($params['site_mode'] == 'private') ? 'show' : '' }}">
                    <div class="card mt-1">
                        <div class="card-header">{{ __('FsLang::panel.site_mode_private_desc') }}</div>
                        <div class="card-body">
                            <!--Config-->
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="site_private_type">{{ __('FsLang::panel.site_mode_private_join_status') }}</label>
                                <select class="form-select" id="site_private_type" name="site_private_status">
                                    <option value="false" {{ $params['site_private_status'] == 'false' ? 'selected' : '' }}>{{ __('FsLang::panel.option_close') }}</option>
                                    <option value="true" {{ $params['site_private_status'] == 'true' ? 'selected' : '' }}>{{ __('FsLang::panel.option_open') }}</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="site_private_plugin">{{ __('FsLang::panel.site_mode_private_join_service') }}</label>
                                <select class="form-select" id="site_private_plugin" name="site_private_service">
                                    <option value="" {{ !$params['site_private_service'] ? 'selected' : '' }}>🚫 {{ __('FsLang::panel.option_deactivate') }}</option>
                                    @foreach ($joinPlugins as $plugin)
                                        <option value="{{ $plugin->fskey }}" {{ $params['site_private_service'] == $plugin->fskey ? 'selected' : '' }}>{{ $plugin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="site_private_end_after">{{ __('FsLang::panel.site_mode_private_content_policy') }}</label>
                                <select class="form-select" id="site_private_end_after" name="site_private_end_after">
                                    <option value="1" {{ $params['site_private_end_after'] == 1 ? 'selected' : '' }}>{{ __('FsLang::panel.site_mode_private_content_policy_1') }}</option>
                                    <option value="2" {{ $params['site_private_end_after'] == 2 ? 'selected' : '' }}>{{ __('FsLang::panel.site_mode_private_content_policy_2') }}</option>
                                </select>
                            </div>
                            <div class="input-group mb-1">
                                <label class="input-group-text" for="site_private_whitelist_roles">{{ __('FsLang::panel.table_whitelist_rules') }}</label>
                                <select class="form-select select2" multiple name="site_private_whitelist_roles[]">
                                    @foreach ($roles as $role)
                                        @if ($role->type != 2)
                                            <option value="{{ $role->id }}" {{ in_array($role->id, $params['site_private_whitelist_roles']) ? 'selected' : '' }}>{{ $role->getLangContent('name', $defaultLanguage) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <!--Config end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--button_save-->
        <div class="row my-3">
            <div class="col-lg-2"></div>
            <div class="col-lg-6">
                <button type="submit" class="btn btn-primary">{{ __('FsLang::panel.button_save') }}</button>
            </div>
        </div>
    </form>

    <!-- Name Language Modal -->
    <div class="modal fade" id="siteNameModal" tabindex="-1" aria-labelledby="siteNameModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('FsLang::panel.button_setting') }}: {{ __('FsLang::panel.site_name') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('panel.update.languages', ['itemKey' => 'site_name']) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap">
                                <thead>
                                    <tr class="table-info">
                                        <th scope="col" class="w-25">{{ __('FsLang::panel.table_lang_tag') }}</th>
                                        <th scope="col" class="w-25">{{ __('FsLang::panel.table_lang_name') }}</th>
                                        <th scope="col" class="w-50">{{ __('FsLang::panel.table_content') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($optionalLanguages as $lang)
                                        <tr>
                                            <td>
                                                {{ $lang['langTag'] }}
                                                @if ($lang['langTag'] == $defaultLanguage)
                                                    <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('FsLang::panel.default_language') }}" data-bs-original-title="{{ __('FsLang::panel.default_language') }}" aria-label="{{ __('FsLang::panel.default_language') }}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $lang['langName'] }}
                                                @if ($lang['areaName'])
                                                    {{ '('.$lang['areaName'].')' }}
                                                @endif
                                            </td>
                                            <td><input type="text" name="languages[{{ $lang['langTag'] }}]" class="form-control" value="{{ $params['site_name'][$lang['langTag']] ?? '' }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--button_save-->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close">{{ __('FsLang::panel.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Description Language Modal -->
    <div class="modal fade" id="siteDescModal" tabindex="-1" aria-labelledby="siteDescModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('FsLang::panel.button_setting') }}: {{ __('FsLang::panel.site_intro') }} - Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('panel.update.languages', ['itemKey' => 'site_desc']) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap">
                                <thead>
                                    <tr class="table-info">
                                        <th scope="col" class="w-25">{{ __('FsLang::panel.table_lang_tag') }}</th>
                                        <th scope="col" class="w-25">{{ __('FsLang::panel.table_lang_name') }}</th>
                                        <th scope="col" class="w-50">{{ __('FsLang::panel.table_content') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($optionalLanguages as $lang)
                                        <tr>
                                            <td>
                                                {{ $lang['langTag'] }}
                                                @if ($lang['langTag'] == $defaultLanguage)
                                                    <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('FsLang::panel.default_language') }}" data-bs-original-title="{{ __('FsLang::panel.default_language') }}" aria-label="{{ __('FsLang::panel.default_language') }}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $lang['langName'] }}
                                                @if ($lang['areaName'])
                                                    {{ '('.$lang['areaName'].')' }}
                                                @endif
                                            </td>
                                            <td><textarea name="languages[{{ $lang['langTag'] }}]" class="form-control" rows="5">{{ $params['site_desc'][$lang['langTag']] ?? '' }}</textarea></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--button_save-->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close">{{ __('FsLang::panel.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Introduction Language Modal -->
    <div class="modal fade" id="siteIntroModal" tabindex="-1" aria-labelledby="siteIntroModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('FsLang::panel.button_setting') }}: {{ __('FsLang::panel.site_intro') }} - Introduction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-text mb-3">{{ __('FsLang::tips.markdown_editor') }}</div>
                    <form action="{{ route('panel.update.languages', ['itemKey' => 'site_intro']) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap">
                                <thead>
                                    <tr class="table-info">
                                        <th scope="col">{{ __('FsLang::panel.table_lang_tag') }}</th>
                                        <th scope="col">{{ __('FsLang::panel.table_lang_name') }}</th>
                                        <th scope="col" class="w-75">{{ __('FsLang::panel.table_content') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($optionalLanguages as $lang)
                                        <tr>
                                            <td>
                                                {{ $lang['langTag'] }}
                                                @if ($lang['langTag'] == $defaultLanguage)
                                                    <i class="bi bi-info-circle text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('FsLang::panel.default_language') }}" data-bs-original-title="{{ __('FsLang::panel.default_language') }}" aria-label="{{ __('FsLang::panel.default_language') }}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $lang['langName'] }}
                                                @if ($lang['areaName'])
                                                    {{ '('.$lang['areaName'].')' }}
                                                @endif
                                            </td>
                                            <td><textarea name="languages[{{ $lang['langTag'] }}]" class="form-control" rows="10">{{ $params['site_intro'][$lang['langTag']] ?? '' }}</textarea></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--button_save-->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close">{{ __('FsLang::panel.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--imageZoom-->
    <div class="modal fade image-zoom" id="imageZoom" tabindex="-1" aria-labelledby="imageZoomLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="position-relative image-box">
                <img class="img-fluid" src="">
            </div>
        </div>
    </div>
@endsection
