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
						<a href="/game/patch-notes" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Game_Patch_Notes#}</span>
						</a>
					</li>
				</ol>
			</div>

			<div class="content-bot clear">
			{include file="pages/game_patch_notes_menu.tpl"}
			{include file="pages/game_patch_notes_content.tpl"}
			</div>
		</div>
	</div>
{include file="footer.tpl"}