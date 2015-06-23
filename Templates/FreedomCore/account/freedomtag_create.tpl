{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="alert caution closeable border-4 glow-shadow">
                <div class="alert-inner">
                    <div class="alert-message">
                        <p class="title"><strong>{#Account_Management_FreedomTag_Warning_Title#}</strong></p>
                        <p>{#Account_Management_FreedomTag_Warning_SubTitle#}</p>
                    </div>
                </div>
                <a class="alert-close" href="#" onclick="$(this).parent().fadeOut(250, function() {ldelim} $(this).css({ldelim}opacity:0{rdelim}).animate({ldelim}height: 0{rdelim}, 100, function() {ldelim} $(this).remove(); {rdelim}); {rdelim}); return false;">
                    {#Account_Management_FreedomTag_Warning_Close#}
                </a>
                <span class="clear"><!-- --></span>
            </div>
            <div id="page-header">
                <h3 class="headline">{#Account_Management_FreedomTag_Tooltip#} {$AppName}</h3>
            </div>
            <div id="page-content" class="page-content">
                <form method="POST" action="/account/management/freedomtag-verify/" id="freedomcoreIdForm" novalidate="novalidate">
                    <div class="freedomtag-field">
                        <span class="randomize-freedomTag">
                            <span class="input-text input-text-medium">
                            <input type="text" name="freedomTag" value="" id="freedomTag" class="medium border-5 glow-shadow-2" autocomplete="off" maxlength="12" tabindex="1" required="required" placeholder="{#Account_Management_FreedomTag_Enter_Name#}" />
                                <span class="inline-message " id="freedomTag-message"> </span>
                            </span>
                            <a class="dice" href="javascript:void(0)" id="generate-random-freedomtag">{#Account_Management_FreedomTag_Generate_Random#}</a>
                        </span>
                        <input type="hidden" id="skip" name="skip" value="false" />
                        <input type="hidden" id="ret" name="ret" value="" />
                        <input type="hidden" id="type" name="type" value="FREE" />
                        <div id="freedomTag-Alert" class="alert error border-4">
                            <div class="alert-inner">
                                <div class="alert-message">
                                    <p class="error-desc" id="latinbasic"><strong>{#Account_Management_FreedomTag_Error_Length#}</strong></p>
                                    <p class="error-desc" id="latin"><strong>{#Account_Management_FreedomTag_Error_Length#}</strong></p>
                                    <p class="error-desc" id="cyrillic"><strong>{#Account_Management_FreedomTag_Error_Length#}</strong></p>
                                    <p class="error-desc" id="korean"><strong>{#Account_Management_FreedomTag_Error_Length#}</strong></p>
                                    <p class="error-desc" id="chinese"><strong>{#Account_Management_FreedomTag_Error_Length#}</strong></p>
                                    <p class="error-desc" id="result2"><strong>{#Account_Management_FreedomTag_Error_NumbFirst#}</strong></p>
                                    <p class="error-desc" id="result3"><strong>{#Account_Management_FreedomTag_Error_SpecSymb#}</strong></p>
                                    <p class="error-desc" id="result4"><strong>{#Account_Management_FreedomTag_Error_SND#}</strong></p>
                                    <p class="error-desc" id="result5"><strong>{#Account_Management_FreedomTag_Error_Restricted#}</strong></p>
                                    <p class="error-desc" id="result6"><strong>{#Account_Management_FreedomTag_Error_Same#}</strong></p>
                                </div>
                            </div>
                            <span class="clear"><!-- --></span>
                        </div>
                    </div>
                    <div class="submit-field">
                        <div class="submit-field">
                            <a class="ui-button button1 disabled" href="#" id="button-submit" tabindex="2">
                                <span class="button-left">
                                    <span class="button-right">
                                        {#Account_Management_FreedomTag_Create_FreedomTag#}
                                    </span>
                                </span>
                            </a>
                            <div class="submit-cancel">
                                <a id="skipFtag" href="/account/management" tabindex="1">
                                    {#Account_Management_FreedomTag_Another_Time#}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <span class="clear"></span>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    var inputs = new Inputs('#freedomcoreIdForm');
                    FreedomTagValidator.accountRegion = "EU";
                    FreedomTagValidator.characterLength = {
                        "latinbasic" : {
                            "min" : 3,
                            "max" : 12
                        },
                        "latin" : {
                            "min" : 3,
                            "max" : 12
                        },
                        "cyrillic" : {
                            "min" : 3,
                            "max" : 12
                        },
                        "korean" : {
                            "min" : 2,
                            "max" : 8
                        },
                        "chinese" : {
                            "min" : 2,
                            "max" : 8
                        }
                    };

                });

                //]]>
            </script>
        </div>
    </div>
</div>
<div id="layout-bottom-divider"></div>
{include file='account/account_footer.tpl'}