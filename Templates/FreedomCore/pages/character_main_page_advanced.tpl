{include file="header.tpl"}
	<div id="content">
		<div class="content-top body-top">
			<div class="content-trail">
				<ol class="ui-breadcrumb">
					<li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{$AppName}</span>
						</a>
					</li>
					<li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/community/" rel="np" class="breadcrumb-arrow" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Menu_Community#}</span>
						</a>
					</li>
					<li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/character/" rel="np" itemprop="url">
							<span id="charactername" class="breadcrumb-text" itemprop="name">{$Character.name}</span>
						</a>
					</li>
				</ol>
			</div>
            <div class="content-bot clear">

                <div id="profile-wrapper" class="profile-wrapper profile-wrapper-advanced profile-wrapper-{$Character.side}">
                    {include file='pages/character_profile_sidebar.tpl'}
                    {include file='pages/character_profile_content.tpl'}
                </div>
            </div>
		</div>
	</div>
{include file='footer.tpl'}