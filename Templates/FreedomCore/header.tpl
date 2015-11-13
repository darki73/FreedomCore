{include file="head.tpl"}
<body class="{$Language} {$Page.bodycss}">
    {if $ExpansionTemplate == "TBC"}
        <div class="tbc_video_background">
            <div class="body_bg"></div>
            <div class="video_header">
                <video loop="loop" src="/Templates/{$Template}/images/backgrounds/header-illidan.webm" autoplay></video>
            </div>
            <div class="video_footer"></div>
        </div>
    {/if}
    <div id="nav-client-header" class="nav-client compact">
        <div id="nav-client-bar">
            <div class="grid-container nav-header-content">
                <ul class="nav-list nav-left" id="nav-client-main-menu">
                    <li>
                        <a id="nav-client-home" class="nav-item nav-home" href="/" data-analytics="global-nav" data-analytics-placement="Nav - {$AppName} Icon"></a>
                    </li>
                    <li>
                        <a id="nav-client-shop" class="nav-item nav-link" href="/shop" data-analytics="global-nav" data-analytics-placement="Nav - {#Menu_Shop#}">{#Menu_Shop#}</a>
                    </li>
                    <li>
                        <a id="nav-client-api" class="nav-item nav-link" href="/api" data-analytics="global-nav" data-analytics-placement="Nav - API">API</a>
                    </li>

                    {if !isset($smarty.session.loggedin) || !$smarty.session.loggedin}

                    {else}
                        {if $User.access_level == 4}
                        <li>
                            <a id="nav-client-update" class="nav-item nav-link" href="/Update" data-analytics="global-nav" data-analytics-placement="Nav - Update">Update</a>
                        </li>
                        {/if}
                    {/if}
                </ul>
                {if !isset($smarty.session.loggedin) || !$smarty.session.loggedin}
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
	<div id="wrapper">
		<div id="header">
			<div class="search-bar">
				<form action="/search" method="get" autocomplete="off">
					<div>
						<div class="ui-typeahead-ghost">
							<input type="text" value="" autocomplete="off" readonly="readonly" class="search-field input input-ghost" />
							<input type="search" class="search-field input" name="q" id="search-field" maxlength="200" tabindex="40" alt="{#Search_Field#}" value="{#Search_Field#}" />
						</div>
						<input type="submit" class="search-button" value="" tabindex="41" />
					</div>
				</form>
			</div>
			<h1 id="logo">
				<a href="/">{$AppName}</a>
			</h1>
			<div class="header-plate">
				<ul class="menu" id="menu">
					<li class="menu-home" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/" {if $Page.type == "homepage"} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Main#}</span>
						</a>
					</li>
					<li class="menu-game" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/game/" {if $Page.type == "game" || $Page.type == 'zone'} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Game#}</span>
						</a>
					</li>
					<li class="menu-community" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/community/" {if $Page.type == "community"} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Community#}</span>
						</a>
					</li>
					<li class="menu-media" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/media/" {if $Page.type == "media"} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Media#}</span>
						</a>
					</li>
					<li class="menu-forums" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/forum/" {if $Page.type == "forum"} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Forums#}</span>
						</a>
					</li>
					<li class="menu-services" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a itemprop="url" href="/shop/" {if $Page.type == "shop"} class="menu-active" {/if}>
							<span itemprop="name">{#Menu_Shop#}</span>
						</a>
					</li>
				</ul>
				{if !isset($smarty.session.loggedin) || !$smarty.session.loggedin}
                    <div class="user-plate">
                        <a href="/account/login" class="card-character plate-logged-out">
                            <span class="card-portrait"></span>
                            <span class="wow-login-key"></span>
                            <span class="login-msg">{#Account_Authorization_Required#}</span>
                        </a>
                    </div>
                {else}
                    {if $Characters == 0}
                        <div class="user-plate">
                            <a href="/account/signout">
                                <div class="card-character plate-default no-character"></div>
                                <div class="meta-wrapper meta-no-character ajax-update">
                                    <div class="meta">
                                        <div class="player-name">{$User.username}</div>
                                        {#Account_No_Characters#}<br />
                                        {#Login_Logout#}
                                    </div>
                                </div>
                            </a>
                        </div>
                     {else}
                        {include file="blocks/userplate_block.tpl"}
                     {/if}
				{/if}
			</div>
		</div>