    <div id="nav-client-footer" class="nav-client">
        <div class="footer-content footer-desktop grid-container">
            <div class="nav-section support-feedback">
                <div class="nav-left">
                    <div id="nav-feedback">
                        <a id="nav-client-news" class="nav-item nav-a nav-item-btn" href="/support/" data-analytics="global-nav" data-analytics-placement="Footer - Support">
                            <i class="nav-icon-24-blue nav-icon-question-circle"></i>
                            {#Support#}
                        </a>
                    </div>
                </div>
                <div class="nav-right">
                    <div id="nav-client-region-select">
                        <div class="dropdown dropup pull-right">
                            <a class="dropdown-toggle nav-item" data-toggle="dropdown">
                                <i class="nav-icon-24-blue nav-icon-globe"></i>{$Language}<b class="caret"></b>
                            </a>
                        </div>
                        <div class="dropdown-menu" data-placement="top">
                            <div class="arrow-bottom"></div>
                            <div id="nav-client-international-desktop">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-section">

            </div>
        </div>
    </div>

    <script type="text/javascript">
        //<![CDATA[
        var csrftoken = "625ea365-1f2f-418d-bdab-6eff64de74bb";
        $(function() {
            Locale.dataPath = "/data/i18n.frag";
        });
        var fullTimeDisplay = true;
        //]]>
    </script>

    <script type="text/javascript" src="/Templates/{$Template}/js/bam.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/common/menu.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/third-party/jquery-ui-1.10.2.custom.min.js"></script>
    {if $Page.type == 'account_management'}
        <script type="text/javascript" src="/Templates/{$Template}/js/lobby.js"></script>
    {/if}
    {if $Page.type == 'account_dashboard' && $Page.bodycss != 'servicespage' && $Page.bodycss != 'paymentpage'}
        <script type="text/javascript" src="/Templates/{$Template}/js/account/dashboard.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/dashboard_secondary.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/inputs.js"></script>
    {/if}
    {if $Page.bodycss == 'servicespage'}
        <script type="text/javascript" src="/Templates/{$Template}/js/dataset.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/guild_services.js"></script>
    {/if}
    {if $Page.bodycss == 'paymentpage'}
        <script type="text/javascript" src="/Templates/{$Template}/js/account/payment.js"></script>
    {/if}
    {if $Page.bodycss == 'claimcode'}
        <script type="text/javascript" src="/Templates/{$Template}/js/account/add-game.js"></script>
    {/if}
    {if $Page.type == 'account_parameters'}
        <script type="text/javascript" src="/Templates/{$Template}/js/inputs.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/password.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/email.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomcore.js"></script>
    {/if}

    {if $Page.type == 'account_freedomtag'}
        <script type="text/javascript" src="/Templates/{$Template}/js/inputs.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomtag/freedomtag-validator.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomtag/freedomtag-randomize.js"></script>
    {/if}

    </body>
</html>