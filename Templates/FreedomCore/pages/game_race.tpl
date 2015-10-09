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
						<a href="/game/race" rel="np" class="breadcrumb-arrow" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{#Game_Races#}</span>
						</a>
					</li>
					<li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
						<a href="/game/race/{$Race.race}" rel="np" itemprop="url">
							<span class="breadcrumb-text" itemprop="name">{$Race.race_full_name}</span>
						</a>
					</li>
				</ol>
			</div>
			<div class="content-bot clear">
				<div id="content-subheader">
					<a class="race-parent" href="./">{#WoW_Races#}</a>
												<span class="clear"><!-- --></span>
					<h4>{$Race.race_full_name}</h4>
				</div>
				<span class="clear"><!-- --></span>
				<div class="faction-req">
						{if $Race.can_join_alliance == 1 && $Race.can_join_horde == 1} 
							 <span class="group alliance">{#Alliance#}</span> &nbsp; / &nbsp; <span class="group horde">{#Horde#}</span>
						{else}
							{if $Race.can_join_alliance == 1}
								<span class="group alliance">{#Alliance#}</span>
							{/if}

							{if $Race.can_join_horde == 1}
								<span class="group horde">{#Horde#}</span>
							{/if}
						{/if}
				</div>
				<span class="clear"><!-- --></span>
				<div class="left-col">
					<div class="story-highlight">
						<p>
							{assign "Race_Header_Description" $Race.race_head_description}
							{$smarty.config.$Race_Header_Description}
						</p>
					</div>
					<div class="story-main">
						<p>{assign "Race_Top_Description" $Race.race_top_description}
							{$smarty.config.$Race_Top_Description}</p>
						<div class="story-illustration"></div>
						<p>{assign "Race_Bottom_Description" $Race.race_bottom_description}
							{$smarty.config.$Race_Bottom_Description}</p>
					</div>
					{assign "Race_Start_Title" $Race.start_location_title}
					{assign "Race_Start_Description" $Race.start_location_description}
					{assign "Race_Capital_Title" $Race.capital_title}
					{assign "Race_Capital_Description" $Race.capital_description}
					{assign "Race_Mount_Title" $Race.mount_title}
					{assign "Race_Mount_Description" $Race.mount_description}
					{assign "Race_Leader_Title" $Race.leader_title}
					{assign "Race_Leader_Description" $Race.leader_description}

					<div class="race-basic start-location" style="background-image:url(/Templates/{$Template}/images/game/race/{$Race.race}/start-location.jpg)">
						<h5 class="basic-header"><span class="overview-icon"></span>{#Race_Start_Location#}<span>{$smarty.config.$Race_Start_Title}</span></h5>
						<div class="basic-story">{$smarty.config.$Race_Start_Description}</div>


					{if $Race.race != "worgen"}
					<div class="race-basic home-city" style="background-image:url(/Templates/{$Template}/images/game/race/{$Race.race}/home.jpg)">
						<h5 class="basic-header"><span class="overview-icon"></span>{#Race_Capital#}<span>{$smarty.config.$Race_Capital_Title}</span></h5>
						<div class="basic-story">{$smarty.config.$Race_Capital_Description}</div>

					{/if}

					<div class="race-basic racial-mount" style="background-image:url(/Templates/{$Template}/images/game/race/{$Race.race}/mount.jpg)">
						<h5 class="basic-header"><span class="overview-icon"></span>{#Race_Mount#}<span>{$smarty.config.$Race_Mount_Title}</span></h5>
						<div class="basic-story">{$smarty.config.$Race_Mount_Description}</div>



					{if $Race.race != "pandaren"}
						<div class="race-basic leader" style="background-image:url(/Templates/{$Template}/images/game/race/{$Race.race}/leader.jpg)">
							<h5 class="basic-header"><span class="overview-icon"></span>{#Race_Leader#}<span>{$smarty.config.$Race_Leader_Title}</span></h5>
							<div class="basic-story">{$smarty.config.$Race_Leader_Description}</div>
						</div>
					{/if}

					<span class="clear"><!-- --></span>
							</div>

					<span class="clear"><!-- --></span>
							{if $Race.race != "worgen"}
							</div>
							{/if}

					<span class="clear"><!-- --></span>
					</div>
				</div>

				<div class="right-col">
					<div class="game-scrollbox">
							<div class="wrapper">
								{assign "Ability_One_Title" $Race.racial_ability_one_title}
								{assign "Ability_One_Description" $Race.racial_ability_one_desc}
								{assign "Ability_Two_Title" $Race.racial_ability_two_title}
								{assign "Ability_Two_Description" $Race.racial_ability_two_desc}
								{assign "Ability_Three_Title" $Race.racial_ability_three_title}
								{assign "Ability_Three_Description" $Race.racial_ability_three_desc}
								{assign "Ability_Four_Title" $Race.racial_ability_four_title}
								{assign "Ability_Four_Description" $Race.racial_ability_four_desc}
								{assign "Ability_Five_Title" $Race.racial_ability_five_title}
								{assign "Ability_Five_Description" $Race.racial_ability_five_desc}
								{assign "Ability_Six_Title" $Race.racial_ability_six_title}
								{assign "Ability_Six_Description" $Race.racial_ability_six_desc}
								<div class="scroll-title">{#Race_Racial_Abilities#}</div>
								<div class="scroll-content">
										<div class="feature-list">
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_one_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_One_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_One_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_two_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_Two_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_Two_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_three_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_Three_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_Three_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												{if $Race.racial_ability_four_title != ""}
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_four_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_Four_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_Four_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												{/if}
												{if $Race.racial_ability_five_title != ""}
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_five_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_Five_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_Five_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												{/if}
												{if $Race.racial_ability_six_title != ""}
												<div class="feature-item clear-after">
													<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Race.racial_ability_six_image}.jpg)">
														<span class="frame"></span>
													</span>
													<div class="feature-wrapper">
														<span class="feature-item-title">{$smarty.config.$Ability_Six_Title}</span>
														<p class="feature-item-desc">{$smarty.config.$Ability_Six_Description}</p>
													</div>
												<span class="clear"><!-- --></span>
												</div>
												{/if}
										</div>
								</div>
							</div>
							<div class="scroll-bg"></div>
						</div>
						{include file="pages/game_race_class.tpl"}
						<div class="fansite-link-box">
								<div class="wrapper">
									<span class="fansite-box-title">{#Game_Learn_More#}</span>
									<p>{#Race_Learn_More_Fan#}</p>
									<div id="sdgksdngklsdngl35" class="fansite-group">
										<a href="http://{$Language}.wowhead.com/race={$Race.race_id}" target="_blank">Wowhead</a> 
										<a href="http://wowpedia.ru/wiki/{$Race.race|replace:'-':'_'}" target="_blank">Wowpedia</a>
									</div>
								</div>
							</div>
				</div>
				<span class="clear"><!-- --></span>
				<div class="guide-page-nav">
					<span class="current-guide-title">{$Race.race_full_name}</span>
					<a class="ui-button next-race button1 next" href="{$RaceNavigation.nextrace}">
						<span class="button-left">
							<span class="button-right"> 
								{#Race_Next_Race#} {$RaceNavigation.nextname}
							</span>
						</span>
					</a>
					<a class="ui-button previous-race button1 previous" href="{$RaceNavigation.previousrace}">
						<span class="button-left">
							<span class="button-right"> 
								{#Race_Prev_Race#} {$RaceNavigation.previousname}
							</span>
						</span>
					</a>			
				</div>
			</div>
		</div>
	</div>
{include file="footer.tpl"}