{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <span class="required-legend"><span class="form-required">*</span> <span class="subcategory">{#Account_Management_Payment_Must_FillIn#}</span></span>
                <h2 class="subcategory">{#Account_Parameters#}</h2>
                <h3 class="headline">{#Account_Management_Parameters_Password_Change_Subheader#}</h3>
            </div>
            <div id="page-content" class="page-content">
                <div class="columns-2-1 settings-content">
                    <div class="column column-left">
                        <div class="password-entry">
                            <span class="clear"><!-- --></span>
                            <form method="post" action="/account/management/settings/modify-password" id="change-settings" novalidate="novalidate">
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="oldPassword">
                                            <span class="label-text">
                                            {#Account_Management_Parameters_Password_Change_Old_Label#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="password" id="oldPassword" name="oldPassword" value="" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="16" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Password_Change_Old_Placeholder#}" />
                                            <span class="inline-message " id="oldPassword-message"> </span>
                                        </span>
                                    </span>
                                </div>
                                <input type="hidden" id="Username" value="{$User.username}">
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="newPassword">
                                            <span class="label-text">
                                                {#Account_Management_Parameters_Password_Change_New_Label#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="password" id="newPassword" name="newPassword" value="" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="16" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Password_Change_New_Placeholder#}" />
                                            <span class="inline-message " id="newPassword-message"> </span>
                                        </span>
                                    </span>
                                </div>
                                <div class="input-row input-row-note" id="password-strength" style="display: none">
                                    <div class="input-note input-text-small border-5 glow-shadow">
                                        <div class="input-note-content">
                                            <div class="password-strength">
                                                <span class="password-result">
                                                    {#Account_Management_Parameters_Password_Change_Security_Level#}:
                                                    <strong id="password-result"></strong>
                                                </span>
                                                <span class="password-rating"><span class="rating rating-default" id="password-rating"></span></span>
                                            </div>
                                        </div>
                                        <div class="input-note-arrow"></div>
                                    </div>
                                </div>
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="newPasswordVerify">
                                            <span class="label-text">
                                                {#Account_Management_Parameters_Password_Change_Verify_New_Placeholder#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="password" id="newPasswordVerify" name="newPasswordVerify" value="" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="16" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Password_Change_Verify_New_Placeholder#}" />
                                            <span class="inline-message " id="newPasswordVerify-message"> </span>
                                        </span>
                                    </span>
                                </div>
                                <div class="submit-row" id="submit-row">
                                    <div class="input-left"></div>
                                    <div class="input-right">
                                        <button class="ui-button button1" type="submit" id="password-submit" tabindex="1">
                                            <span class="button-left">
                                                <span class="button-right">{#Account_Management_Parameters_Next#}</span>
                                            </span>
                                        </button>
                                        <a class="ui-cancel " href="/account/management/" tabindex="1">
                                            <span>
                                                {#Account_Management_Parameters_Cancel#}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <script type="text/javascript">
                                //<![CDATA[
                                var FormMsg = {
                                    'headerSingular': '{#Account_Management_Parameters_headerSingular#}',
                                    'headerMultiple': '{#Account_Management_Parameters_headerSingular#}',
                                    'fieldMissing': '{#Account_Management_Parameters_fieldMissing#}',
                                    'fieldsMissing': '{#Account_Management_Parameters_fieldsMissing#}',
                                    'emailInfo': '{#Account_Management_Parameters_emailInfo#}',
                                    'emailMissing': '{#Account_Management_Parameters_emailMissing#}',
                                    'emailInvalid': '{#Account_Management_Parameters_emailInvalid#}',
                                    'emailMismatch': '{#Account_Management_Parameters_emailMismatch#}',
                                    'passwordInvalid': '{#Account_Management_Parameters_passwordInvalid#}',
                                    'passwordMismatch': '{#Account_Management_Parameters_passwordMismatch#}',
                                    'tosDisagree': '{#Account_Management_Parameters_tosDisagree#}',
                                    'taxInvoiceSelect': '{#Account_Management_Parameters_taxInvoiceSelect#}',
                                    'passwordError1': '{#Account_Management_Parameters_passwordError1#}',
                                    'passwordError2': '{#Account_Management_Parameters_passwordError2#}',
                                    'passwordStrength0': '{#Account_Management_Parameters_passwordStrength0#}',
                                    'passwordStrength1': '{#Account_Management_Parameters_passwordStrength1#}',
                                    'passwordStrength2': '{#Account_Management_Parameters_passwordStrength2#}',
                                    'passwordStrength3': '{#Account_Management_Parameters_passwordStrength3#}',
                                    'oldpasswordError': '{#Account_Management_Parameters_oldpasswordError#}'
                                };
                                //]]>
                            </script>
                        </div>
                    </div>
                    <div class="column column-right">
                        <div class="password-requirements">
                            <ul class="password-level" id="password-level">
                                <li id="password-level-0">
                                    <span class="icon-16"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_Password_Change_Rule_One#}</span>
                                </li>
                                <li id="password-level-1">
                                    <span class="icon-16"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_Password_Change_Rule_Two#}</span>
                                </li>
                                <li id="password-level-2">
                                    <span class="icon-16"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_Password_Change_Rule_Three#}</span>
                                </li>
                                <li id="password-level-3">
                                    <span class="icon-16"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_Password_Change_Rule_Four#}</span>
                                </li>
                                <li id="password-level-4">
                                    <span class="icon-16"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_Password_Change_Rule_Five#}</span>
                                </li>
                            </ul>
                            <p class="caption">{#Account_Management_Parameters_Password_Change_Rule_Six#}</p>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    var inputs = new Inputs('#change-settings');

                    var settings = new ChangePassword('#change-settings', {
                        passwordFields: [
                            '#newPassword',
                            '#newPasswordVerify'
                        ],
                        emailAddress: '{$User.email}'
                    });
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}