<html xml:lang="{$Language}" class="{$Language}">
<head xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
    <meta http-equiv="imagetoolbar" content="false" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{$Page.pagetitle} {$AppName}</title>
    <link rel="shortcut icon" href="/Templates/{$Template}/images/meta/favicon.ico" />
    <link rel="search" type="application/opensearchdescription+xml" href="http://{$smarty.server.HTTP_HOST}/data/opensearch" title="{#Head_Opensearch_Meta#} {$AppName}" />
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/common-game-site.css" />
    <link title="{$AppName}® - {#Head_News_Meta#}" href="/feed/news" type="application/atom+xml" rel="alternate"/>

    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
    <!--[if gt IE 8]><!--><link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/toolkit/freedomnet-web.min.css" /><!-- <![endif]-->
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/global.css" />
    {if $Page.type == 'shop'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/browse.css" />
        <style>
            html {
                height: auto;
                min-width: 480px;
            }
            body {
                height: 100%;
            }
            html,
            body {
                background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1920.jpg") no-repeat center -200px !important;
            }
            .body-content {
                background: none !important;
            }
            .navbar-static {
                background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1920.jpg") no-repeat center 0 !important;
            }
            @media (max-width: 1280px) {
                html,
                body {
                    background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1280.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1280..jpg") no-repeat center 0 !important;
                }
            }
            @media (max-width: 1024px) {
                html,
                body {
                    background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1024.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: #281504 url("/Templates/{$Template}/images/backgrounds/family-wow-background-1024.jpg") no-repeat center 0 !important;
                }
            }
        </style>
    {/if}
    {if $Page.type == 'shop-buy'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/purchase.css" />
        <style>
            html {
                height: auto;
                min-width: 480px;
            }
            body {
                height: 100%;
            }
            html,
            body {
                background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1920.jpg") no-repeat center -200px !important;
            }
            .body-content {
                background: none !important;
            }
            .navbar-static {
                background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1920.jpg") no-repeat center 0 !important;
            }
            @media (max-width: 1280px) {
                html,
                body {
                    background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1280.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1280.jpg") no-repeat center 0 !important;
                }
            }
            @media (max-width: 1024px) {
                html,
                body {
                    background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1024.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: #0f2a48 url("/Templates/{$Template}/images/backgrounds/bnet-background-1024.jpg") no-repeat center 0 !important;
                }
            }
        </style>
    {/if}

    {if $Page.type == 'shop-mount'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/product.css" />
        <style>
            html {
                height: auto;
                min-width: 480px;
            }
            body {
                height: 100%;
            }
            html,
            body {
                background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1920.jpg") no-repeat center -200px !important;
            }
            .body-content {
                background: none !important;
            }
            .navbar-static {
                background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1920.jpg") no-repeat center 0 !important;
            }
            @media (max-width: 1280px) {
                html,
                body {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1280.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1280.jpg") no-repeat center 0 !important;
                }
            }
            @media (max-width: 1024px) {
                html,
                body {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1024.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/mounts/{$ItemData.item_background}_1024.jpg") no-repeat center 0 !important;
                }
            }
        </style>
    {/if}
    {if $Page.type == 'shop-item'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/product.css" />
        <style>
            html {
                height: auto;
                min-width: 480px;
            }
            body {
                height: 100%;
            }
            html,
            body {
                background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1920.jpg") no-repeat center -200px !important;
            }
            .body-content {
                background: none !important;
            }
            .navbar-static {
                background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1920.jpg") no-repeat center 0 !important;
            }
            @media (max-width: 1280px) {
                html,
                body {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1280.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1280.jpg") no-repeat center 0 !important;
                }
            }
            @media (max-width: 1024px) {
                html,
                body {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1024.jpg") no-repeat center -200px !important;
                }
                .body-content {
                    background: none !important;
                }
                .navbar-static {
                    background: {$ItemData.item_background_color} url("/Templates/{$Template}/images/shop/items/{$ItemData.item_background}_1024.jpg") no-repeat center 0 !important;
                }
            }
        </style>
    {/if}

    <script src="/Templates/{$Template}/js/third-party/jquery-1.11.0.min.js"></script>
    <script src="/Templates/{$Template}/js/core.min.js"></script>

    <meta name="description" content="{$AppDescription}" />
    <script type="text/javascript">
        //<![CDATA[
        var Core = Core || {},
        Login = Login || {};
        Core.staticUrl = '/Templates/{$Template}';
        Core.sharedStaticUrl = '/Templates/{$Template}';
        Core.baseUrl = 'http://{$smarty.server.HTTP_HOST}';
        Core.projectUrl = '/';
        Core.cdnUrl = '/';
        Core.supportUrl = '';
        Core.secureSupportUrl = '';
        Core.project = 'wow';
        Core.locale = '{$Language}';
        Core.language = '{$Language}';
        Core.region = 'eu';
        Core.shortDateFormat = 'dd/MM/yyyy';
        Core.dateTimeFormat = 'dd/MM/yyyy HH:mm';
        Core.loggedIn = false;
        Core.userAgent = 'web';
        Login.embeddedUrl = '/fragment/login.frag';
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '{$GoogleAnalytics.Account}']);
        _gaq.push(['_setDomainName', '{$GoogleAnalytics.Domain}']);
        _gaq.push(['_setAllowLinker', true]);
        _gaq.push(['_trackPageview']);

        //]]>
    </script>
</head>
