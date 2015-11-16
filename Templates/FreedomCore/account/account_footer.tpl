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
                                <i class="nav-icon-24-blue nav-icon-globe"></i>
                                {if $Language == 'es'}
                                    Español
                                {elseif $Language == 'en'}
                                    English
                                {elseif $Language == 'ru'}
                                    Русский
                                {elseif $Language == 'pt'}
                                    Português
                                {elseif $Language == 'kr'}
                                    한국어
                                {elseif $Language == 'fr'}
                                    Français
                                {elseif $Language == 'de'}
                                    Deutsch
                                {elseif $Language == 'it'}
                                    Italiano
                                {elseif $Language == 'pl'}
                                    Polski
                                {/if}
                                <b class="caret"></b>
                            </a>
                            <div class="dropdown-menu" data-placement="top">
                                <div class="arrow bottom"></div>
                                <div id="nav-client-international-desktop">
                                    <div class="nav-international-container">
                                        <div class="dropdown-section nav-column-container">
                                            <div class="nav-column-50">
                                                <div id="select-regions" class="nav-box regions">
                                                    <h3>{#Item_Seller_Zone#}</h3>
                                                    <ul class="region-ul">
                                                        <li class="region active current"><a class="nav-item select-region" href="javascript:;" data-target="world">World</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="nav-column-50">
                                                <div id="select-language" class="nav-box languages">
                                                    <h3>{#Community_Realms_Language#}</h3>
                                                    <div class="region region-languages active current" data-region="eu">
                                                        <ul class="region-ul">
                                                            <li class="{if $Language == 'de'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/de/" data-target="world" data-language="de-de">Deutsch</a>
                                                            </li>
                                                            <li class="{if $Language == 'en'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/en/" data-target="world" data-language="en-gb">English</a>
                                                            </li>
                                                            <li class="{if $Language == 'es'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/es/" data-target="world" data-language="es-es">Español</a>
                                                            </li>
                                                            <li class="{if $Language == 'fr'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/fr/" data-target="world" data-language="fr-fr">Français</a>
                                                            </li>
                                                            <li class="{if $Language == 'it'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/it/" data-target="world" data-language="it-it">Italiano</a>
                                                            </li>
                                                            <li class="{if $Language == 'pl'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/pl/" data-target="world" data-language="pl-pl">Polski</a>
                                                            </li>
                                                            <li class="{if $Language == 'pt'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/pt/" data-target="world" data-language="pt-pt">Português</a>
                                                            </li>
                                                            <li class="{if $Language == 'ru'}active current{/if}">
                                                                <a class="nav-item select-language" href="/changelanguage/ru/" data-target="world" data-language="ru-ru">Русский</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-section dropdown-well nav-box localeChange">
                                        <a id="nav-client-change-language-desktop" href="javascript:;" class="nav-lang-change nav-btn disabled">{#Change#}</a>
                                    </div>
                                </div>
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
        {if isset($CSRFToken)}
        var csrftoken = "{$CSRFToken}";
        {/if}
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
    {if $Page.bodycss == 'restoration'}
        <script type="text/javascript" src="/Templates/{$Template}/js/dataset.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/restoration.js"></script>
    {/if}
    {if $Page.type == 'account_parameters'}
        <script type="text/javascript" src="/Templates/{$Template}/js/inputs.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/password.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/account/email.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomcore.js"></script>
    {/if}

    {if $Page.type == 'admin'}
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomcore_admin.js"></script>
    {/if}

    {if $Page.type == 'account_freedomtag'}
        <script type="text/javascript" src="/Templates/{$Template}/js/inputs.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomtag/freedomtag-validator.js"></script>
        <script type="text/javascript" src="/Templates/{$Template}/js/freedomtag/freedomtag-randomize.js"></script>
    {/if}

    {if $Page.bodycss == 'services-home'}
        {include file = 'parts/MSG_Javascript.tpl'}
        <script type="text/javascript" src="/Templates/{$Template}/js/cms.min.js"></script>
    {/if}

    </body>
</html>