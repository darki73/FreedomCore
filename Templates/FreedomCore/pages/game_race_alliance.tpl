<div class="racegroup alliance">
<span class="race-title">{#Alliance#}</span>
	{foreach $AllianceRaces as $Alliance}
		<div class="flag-card {$Alliance.race_link}">
			<div class="wrapper">
				<a href="/game/race/{$Alliance.race_link}">
					<span class="class-name">{$Alliance.race_name}</span>
					<span class="class-desc">
							{$Alliance.race_description}
					</span>
				</a>
			</div>
		</div>
	{/foreach}
</div>