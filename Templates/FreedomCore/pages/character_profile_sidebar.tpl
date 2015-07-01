<div class="profile-sidebar-anchor">
    <div class="profile-sidebar-outer">
        <div class="profile-sidebar-inner">
            <div class="profile-sidebar-contents">
                <div class="profile-info-anchor">
                    <div class="profile-info">
                        <div class="name">
                            <a href="/character/{$Character.name}/" rel="np">{$Character.name}</a>
                        </div>
                        <div class="title-guild">
                            <div class="title">{$Character.title}</div>
                            {if $Character.guild_name != ''}
                            <div class="guild">
                                <a href="/guild/{$Character.guild_name}/?character={$Character.name}">{$Character.guild_name}</a>
                            </div>
                            {/if}
                        </div>
                        <span class="clear"><!-- --></span>
                        <div class="under-name color-c{$Character.class}">
                            <a href="/game/race/{$Character.race_data.name}" class="race">{$Character.race_data.translation}</a>-<a href="/game/class/{$Character.class_data.name}" class="class">{$Character.class_data.translation}</a> (<a id="profile-info-spec" href="#talents" class="spec tip">
                                {foreach $Specializations as $Spec}
                                    {if $Spec.spec == $Spec.activespec}
                                        {$Spec.Description}
                                    {/if}
                                {/foreach}
                            </a>) <span class="level"><strong>{$Character.level}</strong></span> {#LevelShort#}
                        </div>
                        <div class="achievements"><a href="/character/{$Character.name}/achievement">{$Character.apoints}</a></div>
                    </div>
                </div>
                <ul class="profile-sidebar-menu" id="profile-sidebar-menu">
                    {include file='blocks/character_sidebar.tpl'}
                </ul>
                <div class="summary-sidebar-links">
				    <span class="summary-sidebar-button">
				        <a href="javascript:;" id="summary-link-tools" class="summary-link-tools"></a>
					</span>
                    <span class="summary-sidebar-button">
	                    <a href="javascript:;" data-fansite="character|{$Character.name}" class="fansite-link "> </a>
					</span>
                </div>
            </div>
        </div>
    </div>
</div>