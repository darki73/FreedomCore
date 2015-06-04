<div class="summary-bottom-sub-content">
    <div class="summary-bottom-right">
        <div class="profile-recentactivity">
            <h3 class="category ">
                <a href="feed" class="view-more">Recent Activity</a>
            </h3>
            <div class="profile-box-simple">
                <ul class="activity-feed">
                    <li>
                        <dl>
                            <dd>
                                <a href="/item/49623" class="color-5" data-item="49623">
		                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/inv_axe_113.jpg&quot;);"></span>
                                </a>
                                Obtained <a href="/item/49623" class="color-q5" data-item="49623">Shadowmourne</a>.
                            </dd>
                            <dt>5 days ago</dt>
                        </dl>
                    </li>
                </ul>
                <div class="profile-linktomore">
                    <a href="/character/{$Character.name}/feed" rel="np">View earlier activity</a>
                </div>

                <span class="clear"><!-- --></span>
            </div>
        </div>
    </div>
    <div class="summary-bottom-left">
        <div class="summary-battlegrounds">
            <a href="/character/{$Character.name}/pvp" class="link">Player vs. Player</a>
            <ul>
                {foreach $ArenaRating as $Rating}
                    <li class="rating">
                        <span class="value">{$Rating.personalRating}</span>
                        <span class="name">{if $Rating.type == 2}2v2 arena Rating{elseif $Rating.type == 3}3v3 arena Rating{else}5v5 arena Rating{/if}</span>
                    </li>
                {/foreach}
            </ul>
        </div>
        <div class="summary-professions">
            <h3 class="category ">
                <a href="/character/{$Character.name}/profession/" class="view-more">{#Game_Professions#}</a>
            </h3>
            <ul>
                {foreach $Professions as $Profession}
                    {if $Profession.primary == 1}
                        {$ComputeProfessionBar = ($Profession.current / $Profession.max) * 100}
                        <li>
                            <div class="profile-progress border-3">
                                <div class="bar border-3 hover" style="width: {$ComputeProfessionBar}%"></div>
                                <div class="bar-contents">
                                    <a class="profession-details" href="/character/{$Character.name}/profession/{$Profession.name}">
							<span class="icon">
                                <span class="icon-frame frame-12 ">
                                    <img src="/Templates/{$Template}/images/icons/small/trade_{$Profession.name}.jpg" alt="" width="12" height="12" />
                                </span>
                            </span>
                                        <span class="name">{$Profession.translation}</span>
                                        <span class="value">{$Profession.current}</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
</div>