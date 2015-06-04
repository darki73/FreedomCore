<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$Language}" class="{$Language}">
<head xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
    <meta http-equiv="imagetoolbar" content="false" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{$Page.pagetitle} {$AppName}</title>
    <link rel="shortcut icon" href="/Templates/{$Template}/images/meta/favicon.ico" />
    <link rel="search" type="application/opensearchdescription+xml" href="http://{$smarty.server.HTTP_HOST}/data/opensearch" title="{#Head_Opensearch_Meta#} {$AppName}" />
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/toolkit/wow-web.min.css" />

    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/login/global.min.css" />
    <script type="text/javascript" src="/Templates/{$Template}/js/third-party/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="/Templates/{$Template}/js/core.min.js"></script>
    <meta name="viewport" content="width=device-width" />

</head>
<body class="{$Language} login-template web embedded" data-embedded-state="STATE_LOGIN">
<div class="grid-container wrapper" id="embedded-login">
    <a class="close icon-remove icon-white" data-dismiss="modal" href="#" id=
    "embedded-close" onclick="updateParent('close')" style=
       "font-style: italic"></a>

    <h1 class="logo">{#Account_Login#} {$AppName}</h1>

    <div class="hide" id="info-wrapper">
        <h2><strong class="info-title"></strong></h2>

        <p class="info-body"></p>
        <button class="btn btn-block hide visible-phone" id="info-phone-close">Close</button>
    </div>

    <div class="input" id="login-wrapper">
        <form action="/account/performlogin" class=" username-required input-focus" id=
        "password-form" method="post" name="password-form">
            <div class="control-group">
                <label class="control-label" for="accountName" id=
                "accountName-label">E-mail</label>

                <div class="controls">
                    <input class="input-block input-large" id="accountName"
                           maxlength="320" name="accountName" placeholder="{#Login_Email#}"
                           spellcheck="false" tabindex="1" title="Ваш E-mail" type=
                           "text">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="password" id=
                "password-label">{#Password#}</label>

                <div class="controls">
                    <input autocomplete="off" class="input-block input-large"
                           id="password" maxlength="16" name="password" placeholder=
                    "{#Login_Password#}" spellcheck="false" tabindex="1" title=
                           "{#Login_Password#}" type="password">
                </div>
            </div>

            <div class="persistWrapper">
                <label class="checkbox-label css-label" for="persistLogin" id=
                "persistLogin-label"><input checked="checked" id="persistLogin"
                                            name="persistLogin" tabindex="1" type="checkbox">
                    <span class="input-checkbox"></span>{#Login_Remember_Me#}</label>
            </div>

            <div class="control-group submit">
                <button class="btn btn-primary btn-large btn-block"
                        data-loading-text="" id="submit" tabindex="1" type=
                "submit">{#Login_Authorization#}<i class="spinner-battlenet"></i></button>
            </div>
            {if isset($ReturnTo)}
                <input type="hidden" name="returnto" id="returnto" value="{$ReturnTo}">
            {/if}
            <ul id="help-links">
                <li>
                    <a class="btn btn-block btn-large" href=
                    "/account/create"
                       rel="external" tabindex="1">{#Login_Create_Account#}
                        <i class="icon-external-link"></i></a>
                </li>

                <li>
                    <a class="" href=
                    "/account/restore"
                       rel="external" tabindex="1">{#Login_Cant_Login#} <i class=
                                                                           "icon-external-link"></i></a>
                </li>
            </ul><input id="csrftoken" name="csrftoken" type="hidden" value=
            "{$CSRFToken}">
        </form>
    </div>
</div>
<script type="text/javascript" src="/Templates/{$Template}/js/embed-0.1.5.min.js"></script>
<script type="text/javascript" src="/Templates/{$Template}/js/login/toolkit.min.js"></script>
<script type="text/javascript" src="/Templates/{$Template}/js/login/global.min.js"></script>
<script type="text/javascript" src="/Templates/{$Template}/js/login/login.min.js"></script>
</body>
</html>