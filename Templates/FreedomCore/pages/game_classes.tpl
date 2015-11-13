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
						<a href="/game/class" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Game_Classes#}</span>
						</a>
					</li>
				</ol>
			</div>

			<div class="content-bot clear">

				<div class="section-title">
					<h2>{#Game_Classes#}</h2>
				</div>
				<p class="main-header-desc">
					{#Game_Classes_Desc#}
				</p>
				{foreach $Classes as $Class name=ClassLoop}
					{if $smarty.foreach.ClassLoop.index % 2}
						<div class="flag-card {$Class.class_name}" style="float:right">
							<div class="wrapper">
								<a href="/game/class/{$Class.class_name}">
									<span class="class-name">{$Class.class_full_name}</span>
									<span class="class-type">
									{if $Class.can_be_tank}
										{#Class_Role_Tank#},
									{/if}
									{if $Class.can_be_heal}
										{#Class_Role_Healer#},
									{/if}
									{if $Class.melee_damage}
										{#Class_Role_DPS#} ({#Damage_Type_Melee#}){if $Class.ranged_physical},{/if}{if $Class.ranged_arcane},{/if}
									{/if}
									{if $Class.ranged_physical}
										{#Class_Role_DPS#} ({#Damage_Type_Ranged_Physical#}){if $Class.ranged_arcane},{/if}
									{/if}
									{if $Class.ranged_arcane}
										{#Class_Role_DPS#} ({#Damage_Type_Ranged_Arcane#})
									{/if}
									</span>
									<span class="class-desc">{$Class.class_description_classes}</span>
								</a>
							</div>
						</div>
					{else}
						<div class="flag-card {$Class.class_name}">
							<div class="wrapper">
								<a href="/game/class/{$Class.class_name}">
									<span class="class-name">{$Class.class_full_name}</span>
									<span class="class-type">
									{if $Class.can_be_tank}
										{#Class_Role_Tank#},
									{/if}
									{if $Class.can_be_heal}
										{#Class_Role_Healer#},
									{/if}
									{if $Class.melee_damage}
										{#Class_Role_DPS#} ({#Damage_Type_Melee#}){if $Class.ranged_physical},{/if}{if $Class.ranged_arcane},{/if}
									{/if}
									{if $Class.ranged_physical}
										{#Class_Role_DPS#} ({#Damage_Type_Ranged_Physical#}){if $Class.ranged_arcane},{/if}
									{/if}
									{if $Class.ranged_arcane}
										{#Class_Role_DPS#} ({#Damage_Type_Ranged_Arcane#})
									{/if}
									</span>
									<span class="class-desc">{$Class.class_description_classes}</span>
								</a>
							</div>
						</div>
					{/if}
				{/foreach}
				<span class="clear"><!-- --></span>
			</div>
		</div>
	</div>
{include file="footer.tpl"}