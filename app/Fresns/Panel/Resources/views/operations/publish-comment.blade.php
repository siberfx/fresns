@extends('FsView::commons.sidebarLayout')

@section('sidebar')
    @include('FsView::operations.sidebar')
@endsection

@section('content')
    <!--publish header-->
    <div class="row mb-4">
        <div class="col-lg-7">
            <h3>{{ __('FsLang::panel.sidebar_publish') }}</h3>
            <p class="text-secondary">{{ __('FsLang::panel.sidebar_publish_intro') }}</p>
        </div>
        <div class="col-lg-5">
            <div class="input-group mt-2 mb-4 justify-content-lg-end">
                {{-- <a class="btn btn-outline-secondary" href="#" role="button">{{ __('FsLang::panel.button_support') }}</a> --}}
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link" href="{{ route('panel.publish.post.index') }}">{{ __('FsLang::panel.sidebar_publish_tab_post') }}</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('panel.publish.comment.index') }}">{{ __('FsLang::panel.sidebar_publish_tab_comment') }}</a></li>
        </ul>
    </div>
    <!--publish config-->
    <form action="{{ route('panel.publish.comment.update') }}" id="publishComment" method="post">
        @csrf
        @method('put')
        <!--publish_comment_verify_config-->
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.publish_comment_verify_config') }}:</label>
            <div class="col-lg-6 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="comment_required_email" id="comment_required_email" value="true" {{ $params['comment_required_email'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="comment_required_email">{{ __('FsLang::panel.permission_option_email') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="comment_required_phone" id="comment_required_phone" value="true" {{ $params['comment_required_phone'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="comment_required_phone">{{ __('FsLang::panel.permission_option_phone') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="comment_required_kyc" id="comment_required_kyc" value="true" {{ $params['comment_required_kyc'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="comment_required_kyc">{{ __('FsLang::panel.permission_option_kyc') }}</label>
                </div>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.publish_verify_desc') }}</div>
        </div>
        <!--publish_comment_rules_config-->
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.publish_comment_rules_config') }}:</label>
            <div class="col-lg-6 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="comment_limit_status" id="comment_limit_status_0" value="false" data-bs-toggle="collapse" data-bs-target=".comment_limit_setting.show" aria-expanded="false" aria-controls="comment_limit_setting" {{ !$params['comment_limit_status'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="comment_limit_status_0">{{ __('FsLang::panel.option_close') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="comment_limit_status" id="comment_limit_status_1" value="true" data-bs-toggle="collapse" data-bs-target=".comment_limit_setting:not(.show)" aria-expanded="false" aria-controls="comment_limit_setting" {{ $params['comment_limit_status'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="comment_limit_status_1">{{ __('FsLang::panel.option_open') }}</label>
                </div>
                <!--rules_config-->
                <div class="collapse comment_limit_setting mt-3 {{ $params['comment_limit_status'] ? 'show' : '' }}">
                    <div class="input-group mb-3">
                        <label class="input-group-text fresns-label" id="post_limit_status">{{ __('FsLang::panel.publish_rule_type') }}</label>
                        <select class="form-select" id="comment_limit_type" name="comment_limit_type">
                            <option value="1" {{ $params['comment_limit_type'] == '1' ? 'selected' : '' }}>{{ __('FsLang::panel.permission_option_rule_datetime') }}</option>
                            <option value="2" {{ $params['comment_limit_type'] == '2' ? 'selected' : '' }}>{{ __('FsLang::panel.permission_option_rule_time') }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3 collapse @if ($params['comment_limit_type'] == '1') show @endif" id="comment_datetime_setting">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.publish_rule_datetime') }}</label>
                        <input type="datetime-local" name="comment_limit_period_start" value="{{ $params['comment_limit_period_start'] }}" class="form-control" placeholder="2022/01/01 22:00:00">
                        <input type="datetime-local" name="comment_limit_period_end" value="{{ $params['comment_limit_period_end'] }}" class="form-control" placeholder="2022/01/05 09:00:00">
                    </div>
                    <div class="input-group mb-3 collapse @if ($params['comment_limit_type'] == '2') show @endif" id="comment_time_setting">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.publish_rule_time') }}</label>
                        <input type="time" name="comment_limit_cycle_start" value="{{ $params['comment_limit_cycle_start'] }}" class="form-control" placeholder="22:00:00">
                        <input type="time" name="comment_limit_cycle_end" value="{{ $params['comment_limit_cycle_end'] }}" class="form-control" placeholder="09:00:00">
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.publish_rule_timezone') }}</label>
                        <div class="form-control bg-white">
                            {{ $ruleTimezone }}
                            ({{ __('FsLang::panel.system_info_database_timezone') }})
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.publish_rule_rule') }}</label>
                        <div class="form-control bg-white">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="comment_limit_rule" id="post.limit.rule.1" value="1" {{ $params['comment_limit_rule'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="post.limit.rule.1">{{ __('FsLang::panel.permission_option_review_publish') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="comment_limit_rule" id="post.limit.rule.2" value="2" {{ $params['comment_limit_rule'] == '2' ? 'checked' : '' }}>
                                <label class="form-check-label" for="post.limit.rule.2">{{ __('FsLang::panel.permission_option_close_publish') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.publish_rule_tip') }}</label>
                        <button class="btn btn-outline-secondary text-start fresns-control name-button" type="button" data-bs-toggle="modal" data-bs-target="#langModal">{{ $defaultLangParams['comment_limit_tip'] }}</button>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text fresns-label">{{ __('FsLang::panel.table_whitelist_rules') }}</label>
                        <select class="form-select select2" name="comment_limit_whitelist[]" multiple="multiple">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @if ($params['comment_limit_whitelist'] && is_array($params['comment_limit_whitelist']) && in_array($role->id, $params['comment_limit_whitelist'])) selected @endif>
                                    {{ $role->getLangContent('name', $defaultLanguage) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--rules_config end-->
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.publish_rules_desc') }}</div>
        </div>
        <!--publish_editor_config-->
        <div class="row mb-5">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.publish_editor_config') }}:</label>
            <div class="col-lg-6">
                <select class="form-select" id="comment_editor" name="comment_editor_service">
                    <option value="">{{ __('FsLang::panel.option_default') }}</option>
                    @foreach ($plugins as $plugin)
                        <option value="{{ $plugin->fskey }}" @if ($plugin->fskey == $params['comment_editor_service']) selected @endif>{{ $plugin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.publish_editor_desc') }}</div>
        </div>
        <!--publish_editor_function_status-->
        <div class="row mb-5">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.publish_editor_function_status') }}:</label>
            <div class="col-lg-10">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_sticker" value="true" name="comment_editor_sticker" {{ $params['comment_editor_sticker'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_sticker">{{ __('FsLang::panel.editor_sticker') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_image" value="true" name="comment_editor_image" {{ $params['comment_editor_image'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_image">{{ __('FsLang::panel.editor_image') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_video" value="true" name="comment_editor_video" {{ $params['comment_editor_video'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_video">{{ __('FsLang::panel.editor_video') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_audio" value="true" name="comment_editor_audio" {{ $params['comment_editor_audio'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_audio">{{ __('FsLang::panel.editor_audio') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_document" value="true" name="comment_editor_document" {{ $params['comment_editor_document'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_document">{{ __('FsLang::panel.editor_document') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_mention" value="true" name="comment_editor_mention" {{ $params['comment_editor_mention'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_mention">{{ __('FsLang::panel.editor_mention') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_hashtag" value="true" name="comment_editor_hashtag" {{ $params['comment_editor_hashtag'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_hashtag">{{ __('FsLang::panel.editor_hashtag') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_extend" value="true" name="comment_editor_extend" {{ $params['comment_editor_extend'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_extend">{{ __('FsLang::panel.editor_extend') }}</label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_lbs" value="true" name="comment_editor_location" {{ $params['comment_editor_location'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_lbs">{{ __('FsLang::panel.editor_location') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editor_anonymous" value="true" name="comment_editor_anonymous" {{ $params['comment_editor_anonymous'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="editor_anonymous">{{ __('FsLang::panel.editor_anonymous') }}</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--publish_editor_function_options-->
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end">{{ __('FsLang::panel.publish_editor_function_options') }}:</label>
            <div class="col-lg-10">
                <div class="input-group">
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_image_type') }}</label>
                    <select class="form-select" name="comment_editor_image_upload_method" id="comment_editor_image_upload_method">
                        <option value="api" @if ($params['comment_editor_image_upload_method'] == 'api') selected @endif>Fresns API</option>
                        <option value="page" @if ($params['comment_editor_image_upload_method'] == 'page') selected @endif @if (!$pluginPageUpload['image']) disabled @endif>Plugin Page</option>
                        <option value="sdk" @if ($params['comment_editor_image_upload_method'] == 'sdk') selected @endif>S3 SDK</option>
                    </select>
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_image_number') }}</label>
                    <input type="number" class="form-control input-number" id="comment_editor_image_max_upload_number" name="comment_editor_image_max_upload_number" value="{{ $params['comment_editor_image_max_upload_number'] }}">
                    <span class="input-group-text">{{ __('FsLang::panel.unit_number') }}</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end"></label>
            <div class="col-lg-10">
                <div class="input-group">
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_video_type') }}</label>
                    <select class="form-select" name="comment_editor_video_upload_method" id="comment_editor_video_upload_method">
                        <option value="api" @if ($params['comment_editor_video_upload_method'] == 'api') selected @endif>Fresns API</option>
                        <option value="page" @if ($params['comment_editor_video_upload_method'] == 'page') selected @endif @if (!$pluginPageUpload['video']) disabled @endif>Plugin Page</option>
                        <option value="sdk" @if ($params['comment_editor_video_upload_method'] == 'sdk') selected @endif>S3 SDK</option>
                    </select>
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_video_number') }}</label>
                    <input type="number" class="form-control input-number" id="comment_editor_video_max_upload_number" name="comment_editor_video_max_upload_number" value="{{ $params['comment_editor_video_max_upload_number'] }}">
                    <span class="input-group-text">{{ __('FsLang::panel.unit_number') }}</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end"></label>
            <div class="col-lg-10">
                <div class="input-group">
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_audio_type') }}</label>
                    <select class="form-select" name="comment_editor_audio_upload_method" id="comment_editor_audio_upload_method">
                        <option value="api" @if ($params['comment_editor_audio_upload_method'] == 'api') selected @endif>Fresns API</option>
                        <option value="page" @if ($params['comment_editor_audio_upload_method'] == 'page') selected @endif @if (!$pluginPageUpload['audio']) disabled @endif>Plugin Page</option>
                        <option value="sdk" @if ($params['comment_editor_audio_upload_method'] == 'sdk') selected @endif>S3 SDK</option>
                    </select>
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_audio_number') }}</label>
                    <input type="number" class="form-control input-number" id="comment_editor_audio_max_upload_number" name="comment_editor_audio_max_upload_number" value="{{ $params['comment_editor_audio_max_upload_number'] }}">
                    <span class="input-group-text">{{ __('FsLang::panel.unit_number') }}</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end"></label>
            <div class="col-lg-10">
                <div class="input-group">
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_document_type') }}</label>
                    <select class="form-select" name="comment_editor_document_upload_method" id="comment_editor_document_upload_method">
                        <option value="api" @if ($params['comment_editor_document_upload_method'] == 'api') selected @endif>Fresns API</option>
                        <option value="page" @if ($params['comment_editor_document_upload_method'] == 'page') selected @endif @if (!$pluginPageUpload['document']) disabled @endif>Plugin Page</option>
                        <option value="sdk" @if ($params['comment_editor_document_upload_method'] == 'sdk') selected @endif>S3 SDK</option>
                    </select>
                    <label class="input-group-text">{{ __('FsLang::panel.editor_upload_document_number') }}</label>
                    <input type="number" class="form-control input-number" id="comment_editor_document_max_upload_number" name="comment_editor_document_max_upload_number" value="{{ $params['comment_editor_document_max_upload_number'] }}">
                    <span class="input-group-text">{{ __('FsLang::panel.unit_number') }}</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-lg-2 col-form-label text-lg-end"></label>
            <div class="col-lg-6">
                <div class="input-group">
                    <label class="input-group-text">{{ __('FsLang::panel.publish_editor_comment_content_length') }}</label>
                    <input type="number" class="form-control input-number" id="comment_editor_content_length" name="comment_editor_content_length" value="{{ $params['comment_editor_content_length'] }}">
                    <span class="input-group-text">{{ __('FsLang::panel.unit_character') }}</span>
                </div>
            </div>
            <div class="col-lg-4 form-text pt-1"><i class="bi bi-info-circle"></i> {{ __('FsLang::panel.publish_editor_comment_content_length_desc') }}</div>
        </div>
        <!--button_save-->
        <div class="row mt-5">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <button type="submit" class="btn btn-primary">{{ __('FsLang::panel.button_save') }}</button>
            </div>
        </div>

        <!-- Language Modal -->
        <div class="modal fade" id="langModal" tabindex="-1" aria-labelledby="langModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('FsLang::panel.publish_rule_tip') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
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
                                                <td><textarea class="form-control name-input" name="comment_limit_tip[{{ $lang['langTag'] }}]" rows="3">{{ $params['comment_limit_tip'][$lang['langTag']] ?? '' }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--button_confirm-->
                            <div class="text-center">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close">{{ __('FsLang::panel.button_confirm') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Language Modal End -->
    </form>
@endsection
