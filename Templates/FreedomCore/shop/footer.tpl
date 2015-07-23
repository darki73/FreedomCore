<div id="nav-client-footer" class="nav-client">
    <div class="mobileFooterEnabled footer-content footer-desktop grid-container">
        <div class="nav-section support-feedback">
            <div class="nav-left">
                <div id="nav-feedback">
                    <a id="nav-client-news" class="nav-item nav-a nav-item-btn" href="/support/" data-analytics="global-nav" data-analytics-placement="Footer - Support">
                        <i class="nav-icon-24-blue nav-icon-question-circle"></i>
                        {#Support#}
                    </a>
                </div>
            </div>
        </div>
        <div class="nav-section">
            <div class="nav-left nav-logo-group">
                <div class="footer-logo nav-left">
                    <a class="nav-item logo-link" href="/" data-analytics="global-nav" data-analytics-placement="Footer - {$AppName} Logo"><img class="blizzard-logo" src="/Templates/{$Template}/images/nav-client/blizzard.png" alt="" /></a>
                </div>
                <div class="footer-links nav-left">
                    <a class="nav-item nav-a" href="/company/about/" data-analytics="global-nav" data-analytics-placement="Footer - About">About</a>
                    <span>|</span>
                    <a class="nav-item nav-a" href="/company/privacy" data-analytics="global-nav" data-analytics-placement="Footer - Privacy">Privacy</a>
                    <span>|</span>
                    <a class="nav-item nav-a" href="/company/legal/" data-analytics="global-nav" data-analytics-placement="Footer - Terms">Terms</a>
                    <span>|</span>
                    <a class="nav-item nav-a" href="/api/doc" data-analytics="global-nav" data-analytics-placement="Footer - API">API</a>
                    <div class="copyright">©2015 {$AppName}, Inc.</div>
                    <div class="nav-footer-icon-container">
                        <ul class="nav-footer-icon-list">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nav-ratings">
            </div>
        </div>
    </div>
    <div class="mobileFooterEnabled footer-content footer-mobile grid-container"> <div class="nav-section support-feedback">
            <div id="nav-client-feedback">
                <a id="nav-client-news" class="nav-item nav-a nav-item-btn" href="/support/" data-analytics="global-nav" data-analytics-placement="Footer - Support">
                    <i class="nav-icon-24-blue nav-icon-question-circle"></i>
                    {#Support#}
                </a>
            </div>
        </div>
        <div class="nav-logo-group">
            <div class="footer-logo">
                <a class="nav-item logo-link" href="/" data-analytics="global-nav" data-analytics-placement="Footer - {$AppName} Logo"><img class="blizzard-logo" src="/Templates/{$Template}/images/nav-client/blizzard.png" alt="" /></a>
            </div>
            <div class="footer-links">
                <a class="nav-item nav-a" href="/company/about/" data-analytics="global-nav" data-analytics-placement="Footer - About">About</a>
                <span>|</span>
                <a class="nav-item nav-a" href="/company/privacy" data-analytics="global-nav" data-analytics-placement="Footer - Privacy">Privacy</a>
                <span>|</span>
                <a class="nav-item nav-a" href="/company/legal/" data-analytics="global-nav" data-analytics-placement="Footer - Terms">Terms</a>
                <span>|</span>
                <a class="nav-item nav-a" href="/api/doc" data-analytics="global-nav" data-analytics-placement="Footer - API">API</a>
                <div class="copyright">©2015 {$AppName}, Inc.</div>
            <div class="nav-footer-icon-container">
                <ul class="nav-footer-icon-list">
                </ul>
            </div>
            <div class="nav-ratings">
            </div>
        </div>
    </div>
</div>
    {if isset($ItemData)}
    <script src="/Templates/{$Template}/js/toolkit.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/global.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/product.js"></script>
    {/if}
    <script src="/Templates/{$Template}/js/navbar.js"></script>
    <div id="ajax-indicator" class="ajax-indicator"></div>
</body>
</html>