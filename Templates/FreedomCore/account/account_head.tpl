<html xml:lang="{$Language}" class="ru-ru">
<head xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{$Page.pagetitle} {$AppName}</title>
    <link rel="shortcut icon" href="" />
    <link rel="search" type="application/opensearchdescription+xml" href="http://{$smarty.server.HTTP_HOST}/data/opensearch" title="{#Head_Opensearch_Meta#} {$AppName}" />

    {if $Page.type == 'account_freedomtag'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomnet.css" />
        <link rel="stylesheet" type="text/css" media="print" href="/Templates/{$Template}/css/account/freedomnet-print.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ratings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/inputs.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomtag.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/languages/{$Language}.css" />
    {/if}
    {if $Page.type == 'account_parameters'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomnet.css" />
        <link rel="stylesheet" type="text/css" media="print" href="/Templates/{$Template}/css/account/freedomnet-print.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ratings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/inputs.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/wallet.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/address-book.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/settings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/languages/{$Language}.css" />
    {/if}
    {if $Page.type == 'account_operations'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomnet.css" />
        <link rel="stylesheet" type="text/css" media="print" href="/Templates/{$Template}/css/account/freedomnet-print.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ratings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/orders_history.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/services.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/languages/{$Language}.css" />
    {/if}
    {if $Page.type == 'account_management' || $Page.type == 'admin'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomnet.css" />
        <link rel="stylesheet" type="text/css" media="print" href="/Templates/{$Template}/css/account/freedomnet-print.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ratings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/inputs.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/lobby.css" />
        {if $Page.type == 'admin'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/settings.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/item.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/services.css" />
        {/if}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/languages/{$Language}.css" />
    {/if}
    {if $Page.type == 'admin'}
        {if $Page.bodycss == 'services-home'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/build/cms.min.css" />
        {/if}
    {/if}
    {if $Page.type == 'account_dashboard'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/freedomnet.css" />
        <link rel="stylesheet" type="text/css" media="print" href="/Templates/{$Template}/css/account/freedomnet-print.css" />
        {if $Page.bodycss != 'paymentpage'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/dashboard.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/dashboard_secondary.css" />
        {/if}
        {if $Page.bodycss == 'claimcode'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/add-game.css" />
        {/if}
        {if $Page.bodycss == 'restoration'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/restoration/restoration.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/restoration/item.css" />
        {/if}
        {if $Page.bodycss == 'servicespage'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/guild_services.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/payment_history.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/services.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ui.css" />
        {/if}
        {if $Page.bodycss == 'paymentpage'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/payment.css" />
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/payment_secondary.css" />
        {/if}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/ratings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/account/inputs.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/locale/{$Language}.css" />
        <style>
            #navigation #page-menu h2 {
                font-size: 18px !important;
                letter-spacing: -1px !important;
                line-height: 30px !important;
                margin-top: 7px !important;
            }
        </style>
    {/if}

    <script type="text/javascript" src="/Templates/{$Template}/js/third-party/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/common/bootstrap.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/third-party/class-inheritance.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/third-party/swfobject-2.2.1.min.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/account/common.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/navbar-tk.min.js"></script>

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

<body class="{$Language} {$Page.bodycss}">