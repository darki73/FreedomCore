<div class="user-plate">
    {foreach $Characters as $Character}
        {if $User.pinned_character != null && $Character.guid == $User.pinned_character}
        <a class="card-character plate-{$Character.side} ajax-update" href="/character/{$Character.name}/" id="user-plate" rel="np"></a>
    
        <div class="meta-wrapper meta-{$Character.side} ajax-update">
            <div class="character-card card-game-char ajax-update">
                <div class="message">
                    <span class="player-name">{$User.username}</span>
    
                    <div class="character">
                        <a class="character-name context-link serif name-small"
                        href="/character/{$Character.name}/" rel=
                        "np">{$Character.name}</a>
                        {if $Character.guild_name != null || $Character.guild_name != ''}
                            <div class="guild">
                                <a class="guild-name" href="/guild/{$Character.guild_name}" rel="np">{$Character.guild_name}</a>
                            </div>
                        {/if}
                        <span class="avatar-frame">
                            <img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" class="avatar avatar-wow" />
                            <span class="border"></span> 
                            <span class="icon icon-wow"></span>
                        </span>
    
                        <div class="ui-context character-select" id="context-1">
                            <div class="context">
                                <a class="close" href="javascript:;" onclick=
                                "return CharSelect.close(this);"></a>
    
                                <div class="context-user">
                                    <strong>{$Character.name}</strong>
                                </div>
    
                                <div class="context-links">
                                    <a class="icon-profile link-first" href="/character/{$Character.name}/" rel="np"title="Профиль">
                                        <span class="context-icon"></span>
                                        Профиль
                                    </a> 
                                    <a class="icon-posts" href="/search?f=post&amp;a={$Character.name}&amp;sort=time"rel="np" title="Мои сообщения">
                                        <span class="context-icon"></span>
                                    </a> 
                                    <a class="icon-auctions" href="/vault/character/auction/" rel="np" title="Просмотреть аукцион">
                                        <span class="context-icon"></span>
                                    </a> 
                                    <a class="icon-events link-last" href="/vault/character/event" rel="np" title="Просмотреть события">
                                        <span class="context-icon"></span>
                                    </a>
                                </div>
                            </div>
        {/if}
    {/foreach}
                        <div class="character-list">
                            <div class="primary chars-pane">
                                <div class="char-wrapper">
                                    {foreach $Characters as $Character}
                                    	<a {if $Character.guid == $User.pinned_character}class="char pinned" {else} class="char"{/if} onclick="CharSelect.pin({$Character.guid}, this); return false;" href="/character/{$Character.name}/" rel="np">

	                                    	<span class="pin"></span> 
	                                    	<span class="name">{$Character.name}</span> 
	                                    	<span class="class wow-class-{$Character.class}">{$Character.race_name} – {$Character.class_name}, {$Character.level} {#LevelShort#}</span>
	                                    </a> 
                                    {/foreach}
                                </div>
                                <a class="manage-chars" href="javascript:;" onclick="CharSelect.swipe('in', this); return false;"><span class="plus"></span>
                                Управление персонажами<br>
                                <span>Настройте выпадающее меню
                                персонажа.</span></a>
                            </div>

                            <div class="secondary chars-pane" style=
                            "display: none">
                                <div class="char-wrapper scrollbar-wrapper" id=
                                "scroll">
                                    <div class="scrollbar">
                                        <div class="track">
                                            <div class="thumb"></div>
                                        </div>
                                    </div>

                                    <div class="viewport">
                                        <div class="overview">
                                            {foreach $Characters as $Character}
                                                <a class="wow-class-{$Character.class} pinned" data-tooltip="{$Character.race_name} {$Character.class_name}" href="/character/{$Character.name}/" rel="np">
                                                <span class="icon icon-race">
                                                    <img alt="" height="14" src="/Templates/{$Template}/images/icons/small/race_{$Character.race}_{$Character.gender}.jpg" width="14">
                                                </span>
                                                <span class="icon icon-class">
                                                    <img alt="" height="14" src="/Templates/{$Template}/images/icons/small/class_{$Character.class}.jpg" width="14">
                                                </span> {$Character.level}
                                                    {$Character.name}
                                                </a>
                                            {/foreach}

                                            <div class="no-results hide">
                                                Персонажей не найдено
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="filter">
                                    <input alt="Фильтр" class=
                                    "input character-filter" type="input"
                                    value="Фильтр"><br>
                                    <a href="javascript:;" onclick=
                                    "CharSelect.swipe('out', this); return false;">
                                    К списку персонажей</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>