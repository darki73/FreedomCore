{include file="head.tpl"}
<body class="{$Language} {$Page.bodycss}">
	<div id="wrapper">
        <div id="service" >
            <ul class="service-bar">
                <li class="service-cell service-home">
                    <a accesskey="1" data-action="{$AppName} Home" href=
                    "/" tabindex="50" title="{$AppName}">&nbsp;</a>
                </li>
                {if !$smarty.session.loggedin}
                <li class="service-cell service-welcome">
                    <a href="/account/login">{#Authorize_Account#}</a> {#Or#} <a href=
                                                              "/account/create">{#Create_New_Account_User#}</a>
                </li>
                {else}
                    <li class="service-cell service-welcome">
                        {#Welcome#} {$User.username}
                    </li>
                    {if $User.access_level == 4}
                        <li class="service-cell service-account">
                            <a accesskey="3" class="service-link" data-action="Administrator" href=
                            "/admin/dashboard" tabindex="50">{#Administrator_Title#}</a>
                        </li>
                    {/if}
                {/if}
                <li class="service-cell service-account">
                    <a accesskey="3" class="service-link" data-action="Account" href=
                    "/account/management/" tabindex="50">{#Account_Management#}</a>
                </li>
                {if $smarty.session.loggedin}
                <li class="service-cell service-account">
                    <a accesskey="3" class="service-link" data-action="Account" href=
                    "/account/signout/" tabindex="50">{#Logout#}</a>
                </li>
                {/if}
                <li class="service-cell service-support service-support-enhanced">
                    <a accesskey="4" class="service-link service-link-dropdown"
                       data-action="Support - Support" href="#support" id="support-link"
                       rel="javascript" style="cursor: pointer;" tabindex=
                       "50">{#Support#}<span class="no-support-tickets" id=
                        "support-ticket-count"></span></a>

                    <div class="support-menu" id="support-menu" style="display:none;">
                        <div class="support-primary">
                            <ul class="support-nav">
                                <li>
                                    <a class="support-category" data-action=
                                    "Support - Your Support Tickets" href=
                                       "/account/tickets" id=
                                       "support-nav-tickets" tabindex="55"><strong class=
                                                                                   "support-caption">{#Tickets#}</strong> {#Tickets_Description#}</a>
                                </li>
                            </ul><span class="clear"><!-- --></span>
                        </div>

                        <div class="support-secondary"></div>
                    </div>
                </li>
            </ul>


            <div id="warnings-wrapper">
                <noscript>
                    <div class="warning warning-red" id="javascript-warning">
                        <div class="warning-inner2">
                            Для просмотра сайта требуется поддержка JavaScript.
                        </div>
                    </div></noscript>
            </div>
        </div>
		<div id="header">
			<div class="search-bar">
				<form action="/search" method="get" autocomplete="off">
					<div>
						<div class="ui-typeahead-ghost">
							<input type="text" value="" autocomplete="off" readonly="readonly" class="search-field input input-ghost" />
							<input type="search" class="search-field input" name="q" id="search-field" maxlength="200" tabindex="40" alt="" value="" />
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
				{if !$smarty.session.loggedin}
                    <div class="user-plate">
                        <a href="/account/login" class="card-character plate-logged-out">
                            <span class="card-portrait"></span>
                            <span class="wow-login-key"></span>
                            <span class="login-msg"><strong>Авторизуйтесь</strong>, и перед вами будет еще больше возможностей!</span>
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