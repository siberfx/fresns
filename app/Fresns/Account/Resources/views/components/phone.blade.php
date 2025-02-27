<div class="modal fade" id="editPhoneModal" tabindex="-1" aria-labelledby="editPhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="api-request-form" action="{{ route('account-center.api.update') }}" method="patch" autocomplete="off">
                <input type="hidden" name="formType" value="phone">

                <div class="modal-body">
                    {{-- old phone --}}
                    @if ($accountPassport['phone'])
                        <div id="oldPhone">
                            <p class="form-text mb-3 text-center">{{ $fsLang['settingWarning'] }}</p>
                            <div class="input-group mb-3">
                                <span class="input-group-text">{{ $fsLang['currentPhone'] }}</span>
                                <input class="form-control" type="text" value="{{ $accountPassport['countryCallingCode'].' '.$accountPassport['purePhone'] }}" disabled readonly>
                                <button type="button" class="btn btn-outline-secondary send-verify-code" data-type="sms" data-template-id="4" onclick="sendVerifyCode(this)">{{ $fsLang['sendVerifyCode'] }}</button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">{{ $fsLang['verifyCode'] }}</span>
                                <input type="text" class="form-control" name="verifyCode" id="smsVerifyCodeForPhone" value="">
                                <button type="button" class="btn btn-outline-secondary" data-type="sms" data-template-id="4" data-input-id="smsVerifyCodeForPhone" data-hidden-id="oldPhone" data-show-id="newPhone" data-submit-id="sms-submit" onclick="checkVerifyCode(this)">{{ $fsLang['check'] }}</button>
                            </div>
                        </div>
                    @elseif ($accountPassport['email'])
                        <div id="currentEmail">
                            <p class="form-text mb-3 text-center">{{ $fsLang['settingWarning'] }}</p>
                            <div class="input-group mb-3">
                                <span class="input-group-text">{{ $fsLang['currentEmail'] }}</span>
                                <input class="form-control" type="text" value="{{ $accountPassport['email'] }}" disabled readonly>
                                <button type="button" class="btn btn-outline-secondary send-verify-code" data-type="email" data-template-id="4" onclick="sendVerifyCode(this)">{{ $fsLang['sendVerifyCode'] }}</button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">{{ $fsLang['verifyCode'] }}</span>
                                <input type="text" class="form-control" name="verifyCode" id="emailVerifyCodeForPhone" value="">
                                <button type="button" class="btn btn-outline-secondary" data-type="email" data-template-id="4" data-input-id="emailVerifyCodeForPhone" data-hidden-id="currentEmail" data-show-id="newPhone" data-submit-id="sms-submit" onclick="checkVerifyCode(this)">{{ $fsLang['check'] }}</button>
                            </div>
                        </div>
                    @endif
                    {{-- new phone --}}
                    <div id="newPhone" @if ($accountPassport['email'] || $accountPassport['phone']) class="d-none" @endif>
                        <input type="hidden" name="countryCallingCode" id="countryCallingCode" value="{{ $smsDefaultCode }}">
                        <div class="input-group mb-3">
                            <span class="input-group-text">{{ $accountPassport['phone'] ? $fsLang['newPhone'] : $fsLang['phone'] }}</span>
                            {{-- country code --}}
                            @if (count($smsSupportedCodes) == 1)
                                <span class="input-group-text">+{{ $smsDefaultCode }}</span>
                            @else
                                <button class="btn btn-outline-secondary" type="button" id="countryCallingCodeButton" data-bs-toggle="modal" data-bs-target="#countryCallingCodeModal">+{{ $smsDefaultCode }}</button>
                            @endif

                            {{-- input --}}
                            <input type="number" class="form-control input-number" name="newPurePhone" id="newPhoneInput" placeholder="{{ $fsLang['phone'] }}" required>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text">{{ $fsLang['verifyCode'] }}</span>
                            <input type="text" class="form-control" name="newVerifyCode" value="" required>
                            <button type="button" class="btn btn-outline-secondary send-verify-code" data-type="sms" data-template-id="4" data-account-input-id="newPhoneInput" data-country-calling-code-input-id="countryCallingCode" onclick="sendVerifyCode(this)">{{ $fsLang['sendVerifyCode'] }}</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{ $fsLang['close'] }}</button>
                    <button type="submit" class="btn btn-primary @if ($accountPassport['email'] || $accountPassport['phone']) d-none @endif" id="sms-submit">{{ $fsLang['saveChanges'] }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
