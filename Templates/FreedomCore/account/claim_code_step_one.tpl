{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            {if isset($ErrorCode)}
                <div class="alert error closeable border-4 glow-shadow">
                    <div class="alert-inner">
                        <div class="alert-message">
                            <p class="title">
                                <strong>
                                    {#Account_Management_Claim_Code_Error_Happened#}
                                </strong>
                            </p>
                            <p>
                                {if $ErrorCode == 15011}
                                    {#Account_Management_Claim_Code_Error_Activated#}
                                {elseif $ErrorCode == 15010}
                                    {#Account_Management_Claim_Code_Error_CSRF#}
                                {elseif $ErrorCode == 15015}
                                    {#Account_Management_Claim_Code_Error_GSOFF#}
                                {else}
                                    {#Account_Management_Claim_Code_Error_NotExists#}
                                {/if}
                            </p>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="add-game">
                <div id="page-header">
                    <h2 class="subcategory">{#Account_Management_Service_CS#}</h2>
                    <h3 class="headline">{#Account_Management_Claim_Code#}</h3>
                </div>
                <div class="introduction">
                    <p>
                        {#Account_Management_Claim_Code_Desc#}
                    </p>
                </div>
                <div class="section-box border-5 code-claim">
                    <form method="post" action="/account/management/claim-code-status" id="add-game" onsubmit="return checkEntry(this);">
                        <input type="hidden" id="csrftoken" name="csrftoken" value="{$CSRFToken}" />
                        <input type="hidden" id="accountName" name="accountName" value="{$QueryData.account}" />
                        <input type="hidden" id="character" name="character" value="{$QueryData.character}" />
                        <p class="caption"><label for="key">{#Account_Management_Claim_Code_Enter_Code#}</label></p>
                        <p class="simple-input required">
                            <input type="text" id="key" name="key" value="" class="input border-5 glow-shadow-2 inline-input" maxlength="320" tabindex="1" />
                        </p>

                        <fieldset class="ui-controls ">
                            <button class="ui-button button1" type="submit" id="add-game-submit"><span class="button-left"><span class="button-right">{#Account_Management_Claim_Code_Use_Code#}</span></span></button>
                        </fieldset>
                    </form>
                </div>
                <script type="text/javascript">
                    //<![CDATA[
                    function checkEntry(form) {
                        if (!form.key.value) {
                            $('#add-game p.caption, #key').addClass('form-error');
                            $('#content').prepend(makeErrorBox(['{#Account_Management_Claim_Code_Fill_All_Fields#}']));
                            UI.enableButton($('#add-game-submit'));
                            return false;
                        }
                        return true;
                    }

                    function makeErrorBox(errorMsgs) {
                        $('#content .alert').remove();
                        var errorCount = errorMsgs.length;
                        var errorHtml = ''
                                + '<div class="alert error closeable border-4 glow-shadow">'
                                + '<div class="alert-inner">'
                                + '<div class="alert-message">'
                                + '<p class="title"><strong>{#Account_Management_Claim_Code_Error_Happened#}</strong></p>';
                        if (errorCount>1) {
                            errorHtml += '<ul>';
                            for (var i=0;i<errorCount;i++) {
                                errorHtml += '<li>' + errorMsgs[i] + '</li>';
                            }
                            errorHtml += '</ul>';
                        } else {
                            errorHtml += '<p>' +errorMsgs[0]+ '</p>';
                        }
                        errorHtml += ''
                        + '</div>'
                        + '</div>'
                        + '</div>';
                        return errorHtml;
                    }
                    //]]>
                </script>

            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}