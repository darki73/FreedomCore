<div class="racegroup horde">
<span class="race-title">{#Horde#}</span>
	{foreach $HordeRaces as $Horde}
		<div class="flag-card {$Horde.race_link}">
			<div class="wrapper">
				<a href="/game/race/{$Horde.race_link}">
					<span class="class-name">{$Horde.race_name}</span>
					<span class="class-desc">
							{$Horde.race_description}
					</span>
				</a>
			</div>
		</div>
	{/foreach}
</div>