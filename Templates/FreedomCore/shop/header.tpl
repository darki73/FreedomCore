{include file = 'shop/head.tpl'}
<body class="{$Language} {$Page.bodycss} ">
{include file = 'shop/js_part.tpl'}
<div class="navbar-static">
    <div id="nav-client-header" class="nav-client">
        <div id="nav-client-bar">
            <div class="grid-container nav-header-content">
                <ul class="nav-list nav-left" id="nav-client-main-menu">
                    <li>
                        <a id="nav-client-home" class="nav-item nav-home" href="/" data-analytics="global-nav" data-analytics-placement="Nav - {$AppName} Icon"></a>
                    </li>
                    <li>
                        <a id="nav-client-shop" class="nav-item nav-link {if $Page.type = 'shop'} active{/if}" href="/shop" data-analytics="global-nav" data-analytics-placement="Nav - {#Menu_Shop#}">{#Menu_Shop#}</a>
                    </li>
                </ul>
                {if !$smarty.session.loggedin}
                    <ul class="nav-list nav-right" id="nav-client-account-menu">
                        <li>
                            <div id="username">
                                <div class="dropdown pull-right">
                                    <a class="nav-link username dropdown-toggle" data-toggle="dropdown" rel="navbar">
                                        {#Account_Management#}
                                        <b class="caret"></b>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="arrow top"></div>
                                        <div class="user-profile">
                                            <div class="dropdown-section">
                                                <div class="nav-box">
                                                    <a class="nav-item nav-btn nav-btn-block nav-login-btn" href="/account/login" data-analytics="global-nav" data-analytics-placement="Nav - Account - Log In">{#Login_Authorization#}</a>
                                                </div>
                                            </div>
                                            <div class="dropdown-section">
                                                <ul class="nav-list">
                                                    <li>
                                                        <a class="nav-item nav-a nav-item-box" href="/account/management/" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                            <i class="nav-icon-24-blue nav-icon-character-cog"></i>
                                                            Параметры
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a id="nav-client-support-link" class="nav-item nav-link" href="/support/" data-analytics="global-nav" data-analytics-placement="Nav - {#Support#}"> {#Support#} </a>
                        </li>
                    </ul>
                {else}
                    <ul class="nav-list nav-right" id="nav-client-account-menu">
                        <li>
                            <div id="username">
                                <div class="dropdown pull-right">
                                    <a class="nav-link username dropdown-toggle" data-toggle="dropdown" rel="navbar">
                                        {$User.username}
                                        <b class="caret"></b>
                                    </a>
                                    <div class="dropdown-menu pull-right">
                                        <div class="arrow top"></div>
                                        <div class="user-profile">
                                            {if $User.access_level == 4}
                                                <div class="dropdown-section">
                                                    <ul class="nav-list">
                                                        <li>
                                                            <a class="nav-item nav-a nav-item-box" href="/admin/dashboard" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                                <i class="nav-icon-24-blue nav-icon-character-cog"></i>{#Administrator_Title#}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            {/if}
                                            <div class="dropdown-section">
                                                <div class="nav-box selectable">
                                                    {if $User.freedomtag_name != ''}
                                                        <div class="label">
                                                            <span class="battletag">{$User.freedomtag_name}</span>
                                                            <span class="code">#{$User.freedomtag_id}</span>
                                                        </div>
                                                    {/if}
                                                    <div class="email">{$User.email}</div>
                                                </div>
                                            </div>
                                            <div class="dropdown-section">
                                                <ul class="nav-list">
                                                    <li>
                                                        <a class="nav-item nav-a nav-item-box" href="/account/management/" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                            <i class="nav-icon-24-blue nav-icon-character-cog"></i>{#Account_Management#}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="dropdown-section">
                                                <a class="nav-item nav-item-box" href="/account/signout" data-analytics="global-nav" data-analytics-placement="Nav - Account - Log Out"><i class="nav-icon-24-blue nav-icon-logout"></i>{#Login_Logout#}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a id="nav-client-support-link" class="nav-item nav-link" href="/support/" data-analytics="global-nav" data-analytics-placement="Nav - {#Support#}"> {#Support#} </a>
                        </li>
                    </ul>
                {/if}
            </div>
        </div>
    </div>
    {if isset($PurchaseCompleted)}
        <nav class="navbar">
            <div class="grid-container">
                <ul class="unstyled progress-tracker">
                    <li {if !$PurchaseCompleted} class="active"{/if}>
                        <i class="icon-1-sign"></i>
                        {#Shop_Nav_Pay#}
                    </li>
                    <li {if $PurchaseCompleted} class="active"{/if}>
                        <i class="icon-2-sign"></i>
                        {#Shop_Nav_Play#}!
                    </li>
                </ul>
            </div>
        </nav>
    {else}
        <nav class="navbar">
            <div class="grid-container">
                <div class="grid-50">
                    <ul class="breadcrumb">
                        <li>
                            <a href="/shop/">
                            <span class="breadcrumb-home">
                                <i class="icon-home"></i>
                                {#Menu_Shop#}
                            </span>
                            </a>
                            <span class="divider"><i class="icon-chevron-right icon-white"></i></span>
                        </li>

                        {if !isset($ItemData)}
                            <li class="active">World of Warcraft</li>
                        {else}
                            <li>
                                <a href="/shop/">
                            <span class="breadcrumb-home">
                                World of Warcraft
                            </span>
                                </a>
                                <span class="divider"><i class="icon-chevron-right icon-white"></i></span>
                            </li>
                            <li class="active">{$ItemData.item_name}</li>
                        {/if}
                    </ul>
                </div>
            </div>
        </nav>
    {/if}
</div>