<div class="right-col">
	<div class="game-scrollbox">
		<div class="wrapper">
			<div class="scroll-title">{#Features#}</div>
			<div class="scroll-content">
					<div class="feature-list">
							{foreach $Class.abilities as $Ability}
								<div class="feature-item clear-after">
									<span class="icon-frame-gloss float-left" style="background-image: url(/Templates/{$Template}/images/icons/large/{$Ability.ability_icon}.jpg);">
										<span class="frame"></span>
									</span>
									<div class="feature-wrapper">
										<span class="feature-item-title">{$Ability.ability_name}</span>
										<p class="feature-item-desc">{$Ability.ability_description} </p>
									</div>
									<span class="clear"><!-- --></span>
								</div>
							{/foreach}
					</div>
			</div>
		</div>
		<div class="scroll-bg"></div>
	</div>
	<div class="available-info-box ">
		<div class="available-info-box-title">{#Available_Races#}</div>
		<span class="available-info-box-desc"></span>
		<div class="list-box">
			<div class="wrapper">
					<ul>
					{foreach $Class.can_be_picked_by as $ClassToRace}
						<li>
							<a href="/game/race/{$ClassToRace.race}">
								<span class="icon-frame frame-36" style="background-image: url(/Templates/{$Template}/images/icons/medium/achievement_character_{$ClassToRace.race|replace:'-':''}_male.jpg);"><span class="frame"></span></span>
								<span class="list-title">{$ClassToRace.race_full_name}
										<div>
											{if $ClassToRace.can_join_alliance && $ClassToRace.can_join_horde}
											<span class="list-faction alliance">{#Alliance#}</span>,
											<span class="list-faction horde">{#Horde#}</span>
											{else if $ClassToRace.can_join_alliance}
											<span class="list-faction alliance">{#Alliance#}</span>
											{else}
											<span class="list-faction horde">{#Horde#}</span>
											{/if}
										</div>
								</span>
							</a>
						</li>
					{/foreach}
					</ul>
					<span class="clear"><!-- --></span>
					<span class="clear"><!-- --></span>
			</div>
		</div>
	</div>
</div>