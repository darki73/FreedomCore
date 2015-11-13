<div class="forum-user ajax-update">
    <div class="forum-avatar">
        <div class="bnet-avatar ">
            <div class="avatar-outer">
                <a href="/character/{$CommentCharacter.name}">
                    <img height="64" width="64" src="/Templates/{$Template}/images/2d/avatar/{$CommentCharacter.race}-{$CommentCharacter.gender}.jpg" alt="">
                    <span class="avatar-inner"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="user-details">
        <div class="bnet-username" itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person">
            <div id="context-2" class="ui-context character-select">
                <div class="context">
                    <a href="javascript:;" class="close" onclick="return CharSelect.close(this);"></a>
                    <div class="context-user">
                        <strong>{$CommentCharacter.name}</strong>
                    </div>
                    <div class="context-links">
                        <a href="/character/{$CommentCharacter.name}" title="{#Profile#}" rel="np" class="icon-profile link-first">
                            <span class="context-icon"></span>
                            {#Profile#}
                        </a>
                        <a href="/search?f=post&a={$CommentCharacter.name}&sort=time" title="{#Posts_My#}" rel="np" class="icon-posts">
                            <span class="context-icon"></span>
                        </a>
                        <a href="/vault/character/auction/" title="{#Auction_View#}" rel="np" class="icon-auctions">
                            <span class="context-icon"></span>
                        </a>
                        <a href="/vault/character/event/" title="{#Events_View#}" rel="np" class="icon-events link-last">
                            <span class="context-icon"></span>
                        </a>
                    </div>
                </div>
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
                            {#Account_Characters_Management#}<br>
                            <span>{#Account_Characters_DropDown#}</span></a>
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
                                        {#Account_Characters_Not_Found#}
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
                                {#Account_Characters_Return_Back#}</a>
                        </div>
                    </div>
                </div>
            </div>
            <a href="/character/{$CommentCharacter.name}" itemprop="url" class="context-link wow-class-{$CommentCharacter.class}">
                <span itemprop="name" class="poster-name">{$CommentCharacter.name}</span>
            </a>
        </div>
        <div class="user-character">
            <div class="character-desc">
                <span class="wow-class-{$CommentCharacter.class}">
                    {$CommentCharacter.class_name}-{$CommentCharacter.race_name} {$CommentCharacter.level} {#LevelShort#}
                </span>
            </div>
            <div class="guid" itemprop="affiliation">

            </div>
            <div class="achievements">
                {$CommentCharacter.apoints}
            </div>
        </div>
    </div>
    <span class="clear"></span>
</div>