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
						<a href="/game/" rel="np" class="breadcrumb-arrow" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Menu_Game#}</span>
						</a>
					</li>
					<li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/game/race" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Game_Races#}</span>
						</a>
					</li>
				</ol>
			</div>

			<div class="content-bot clear">

				<div class="section-title">
					<h2>{#Game_Races#}</h2>
				</div>
				<p class="main-header-desc">
					{#Game_Races_Desc#}
				</p>

				{include file="pages/game_race_alliance.tpl"}
				{include file="pages/game_race_horde.tpl"}

				<span class="clear"><!-- --></span>
			</div>
		</div>
	</div>
{include file="footer.tpl"}