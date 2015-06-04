<div class="character-tooltip">
	<span class="icon-frame frame-56">
		<img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" alt="" width="56" height="56" />
		<span class="frame"></span>
	</span>
    <h3>{$Character.name}</h3>
    <div class="color-c{$Character.class}">
        {$Character.class_data.translation}-{$Character.race_data.translation} {$Character.level} {#LevelShort#}
    </div>

    <div class="color-tooltip-{$Character.side}">
        {$Character.guild_name}
    </div>

    <div class="color-tooltip-yellow">Realm Name</div>

    <span class="clear"><!-- --></span>
	<span class="character-spec">
		<span class="icon">
            <span class="icon-frame frame-12 ">
                <img src="" alt="" width="12" height="12" />
            </span>
        </span>
			<span class="name">Spec Name</span>
	<span class="clear"><!-- --></span>
	</span>

</div>