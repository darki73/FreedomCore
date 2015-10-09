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
					<li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/game/" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Menu_Game#}</span>
						</a>
					</li>
				</ol>
			</div>
			<div class="content-bot clear">
				<div id="wiki" class="wiki directory wiki-index">
					<div class="announcement-site">
						<a href="/game/warlords-of-draenor/" class="announcement-site-link">
							{#Game_About_Exp#}
							<span class="arrow"></span>
						</a>
					</div>
					<div class="title">
						<h2>{#Game_Guide#}</h2>
						<p class="desc">{#Game_Guide_Desc#}</p>
					</div>
					<div class="warlords"></div>
					<div class="index">
						<div class="main-buttons">
							<a class="main-button new" href="/game/guide/">
								<h3 style="font-size: 16px;">{#Game_New_Players#}</h3>
								<div class="subtext">{#Game_Getting_Started#}</div>
							</a>
							<a class="main-button returning" href="/game/returning-players-guide/">
								<h3 style="font-size: 16px;">{#Game_Returning_Players#}</h3>
								<div class="subtext">{#Game_Welcome_Back#}</div>
							</a>
							<a class="main-button latest" href="/game/patch-notes/">
								<h3 style="font-size: 16px;">{#Game_Patch_Notes#}</h3>
								<div class="subtext">{#Game_Learn_More#}</div>
							</a>
						</div>
						<div class="panel players">
							<h2 class="header ">{#Game_Player_Heroes#}</h2>
							<a class="circle-link" href="/game/race/">
								<i class="circle-icon race"></i>{#Game_Races#}
							</a>
							<a class="circle-link" href="/game/class/">
								<i class="circle-icon class"></i>{#Game_Classes#}
							</a>
							<a class="circle-link" href="/game/profession/">
								<i class="circle-icon profession"></i>{#Game_Professions#}
							</a>
							<a class="circle-link" href="/game/talent-calculator">
								<i class="circle-icon talents"></i>{#Game_Talents#}
							</a>
						</div>
						<div class="panel gameplay">
							<h2 class="header ">{#Game_Gameplay#}</h2>

							<a class="circle-link" href="/zone/">
								<i class="circle-icon dungeons"></i>{#Game_Dungeons#}
							</a>
							<a class="circle-link" href="/game/pvp/">
								<i class="circle-icon pvp"></i>{#Game_PvP#}
							</a>
							<a class="circle-link" href="/game/garrisons/">
								<i class="circle-icon garrisons"></i>{#Game_Garrisons#}
							</a>
							<a class="circle-link" href="/game/events/">
								<i class="circle-icon events"></i>{#Game_Events#}
							</a>
							<a class="circle-link" href="/game/pet-battles/">
								<i class="circle-icon pets"></i>{#Game_Pet_Battles#}
							</a>
						</div>
						<div class="panel lore">
							<h2 class="header ">{#Game_Lore#}</h2>

							<a class="circle-link" href="/game/lore/">
								<i class="circle-icon lore"></i>{#Game_Tales#}
							</a>
								<a class="circle-link" href="/game/the-story-of-warcraft/">
								<i class="circle-icon story"></i>{#Game_Story#}
							</a>
								<a class="circle-link" href="/game/lore/characters/">
								<i class="circle-icon characters"></i>{#Game_Characters#}
							</a>
						</div>
					</div>
					<span class="clear"><!-- --></span>
				</div>
			</div>
		</div>
	</div>
{include file="footer.tpl"}