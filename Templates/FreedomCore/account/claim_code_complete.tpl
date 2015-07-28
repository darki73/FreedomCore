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
                                {elseif $ErrorCode = 15010}
                                    {#Account_Management_Claim_Code_Error_CSRF#}
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
                        <center>{#Account_Management_Claim_Code_Complete#}</center>
                    </p>
                </div>
                <div class="section-box border-5 code-claim">
                    <div class="product-image thumbnail">
                        <center>
                            <img style="border-radius: 15px;" src="/Templates/{$Template}/images/shop/{$ItemData.category}/{$ItemData.item_shop_icon}_home.jpg" alt="World of Warcraft® In-Game: {$ItemData.item_name}" title="" />
                            <br />
                            <a href="/account/management" class="ui-button button1">{#Account_Management_Back_To_Account#}</a>
                        </center>
                    </div>
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