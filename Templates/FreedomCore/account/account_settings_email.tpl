{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <span class="required-legend"><span class="form-required">*</span> <span class="subcategory">{#Account_Management_Payment_Must_FillIn#}</span></span>
                <h2 class="subcategory">{#Account_Parameters#}</h2>
                <h3 class="headline">{#Account_Management_Parameters_Email_Change_Subheader#}</h3>
            </div>
            <div id="page-content" class="page-content">
                <div class="columns-2-1 settings-content">
                    <div class="column column-left">
                        <div class="email-entry">
                            <span class="clear"></span>
                            <form method="post" action="/account/management/settings/modify-email" id="change-settings" novalidate="novalidate">
                                <input type="hidden" id="username" name="username" value="{$User.username}">
                                <input type="hidden" name="csrftoken" id="csrftoken" value="{$CSRFToken}">
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="newEmail">
                                            <span class="label-text">
                                                {#Account_Management_Parameters_Email_Change_New_Email_Label#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="email" name="newEmail" value="" id="newEmail" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="319" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Email_Change_New_Email_Placeholder#}" />
                                            <span class="inline-message " id="newEmail-message"> </span>
                                        </span>
                                    </span>
                                </div>
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="newEmailVerify">
                                            <span class="label-text">
                                                {#Account_Management_Parameters_Email_Change_Confirm_New_Email_Label#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="email" name="newEmailVerify" value="" id="newEmailVerify" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="319" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Email_Change_Confirm_New_Email_Placeholder#}" />
                                            <span class="inline-message " id="newEmailVerify-message"> </span>
                                        </span>
                                    </span>
                                </div>
                                <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="password">
                                            <span class="label-text">
                                                {#Account_Management_Parameters_Email_Change_Password_Label#}:
                                            </span>
                                            <span class="input-required">*</span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="password" id="password" name="password" value="" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="16" tabindex="1" required="required" placeholder="{#Account_Management_Parameters_Email_Change_Password_Placeholder#}" />
                                            <span class="inline-message " id="password-message"> </span>
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
                        <div class="email-information">
                            <p class="caption">{#Account_Management_Parameters_Email_Change_Hint_One#}</p>
                            <p>{#Account_Management_Parameters_Email_Change_Hint_Two#}</p>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    var inputs = new Inputs('#change-settings');
                    var settings = new ChangeEmail('#change-settings', {
                        emailFields: [
                            '#newEmail',
                            '#newEmailVerify'
                        ],
                        domains: [
                        ]
                    });
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}