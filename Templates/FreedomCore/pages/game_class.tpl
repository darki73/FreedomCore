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
					<li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/game/class" rel="np" class="breadcrumb-arrow" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Game_Classes#}</span>
						</a>
					</li>
					<li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/game/race/{$Class.class_name}" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{$Class.class_full_name}</span>
						</a>
					</li>
				</ol>
			</div>
			<div class="content-bot clear">
				<div id="content-subheader">
					<a class="class-parent" href="./">{#WoW_Classes#}</a>
					<span class="clear"><!-- --></span>
						<h4>{$Class.class_full_name}</h4>
				</div>
				<div class="faction-req"></div>
				<span class="clear"><!-- --></span>
				{include file="pages/game_class_leftcol.tpl"}
				{include file="pages/game_class_rightcol.tpl"}
				<span class="clear"><!-- --></span>
				<div class="guide-page-nav">
					<span class="current-guide-title">{$Class.class_full_name}</span>
						<a class="ui-button next-class button1 next" href="/game/class/{$ClassNavigation.nextclass}">
							<span class="button-left">
								<span class="button-right"> 
									{#Class_Next_Class#} {$ClassNavigation.nextname}
								</span>
							</span>
						</a>

						<a class="ui-button previous-class button1 previous" href="/game/class/{$ClassNavigation.previousclass}">
							<span class="button-left">
								<span class="button-right"> 
									{#Class_Previous_Class#} {$ClassNavigation.previousname}
								</span>
							</span>
						</a>			
				</div>
			</div>
		</div>
	</div>
{include file="footer.tpl"}