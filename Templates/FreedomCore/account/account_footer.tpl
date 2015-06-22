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

    <div class="modal eu-cookie-compliance desktop hide" id="eu-cookie-compliance">
        <div class="modal-header">
            <a class="close" data-dismiss="modal" id="cookie-compliance-close"><i class="icon-remove icon-white"></i></a>
            <h1>Cookie-файлы</h1>
        </div>
        <div class="modal-body">
            <p>На сайтах Blizzard Entertainment используются cookie-файлы и другие аналогичные технологии. Если, прочитав это сообщение, вы остаетесь на нашем сайте, это означает, что вы не возражаете против использования этих технологий.</p>
        </div>
        <button class="btn btn-primary" id="cookie-compliance-agree">Хорошо</button>
        <a class="btn" id="cookie-compliance-learn" href="http://eu.blizzard.com/company/about/privacy.html" target="_blank">Подробнее</a>
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
    {/if}
    {if $Page.bodycss == 'paymentpage'}
        <script type="text/javascript" src="/Templates/{$Template}/js/account/payment.js"></script>
    {/if}

    </body>
</html>