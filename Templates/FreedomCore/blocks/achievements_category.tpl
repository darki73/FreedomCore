<div xml:lang="en" lang="en" style="display: none;">
    <div id="cat-{$Category}" class="container">
        {foreach $Categories as $SelectedCategory}
            {if $SelectedCategory.id == $Category}
                {assign 'CompletedCount' ''}{assign 'PointsCount' ''}{assign 'CompletedAchievementsArray' ''}
                {foreach $CompletedAchievements as $Achievement}
                    {if in_array($Achievement.category, $SelectedCategory)}
                        {$CompletedCount = $CompletedCount + 1}
                        {$PointsCount = $PointsCount + $Achievement.points}
                    {elseif isset($SelectedCategory.subcategories)}
                        {if in_array($Achievement.category, $SelectedCategory.subcategories)}
                            {$CompletedCount = $CompletedCount + 1}
                            {$PointsCount = $PointsCount + $Achievement.points}
                        {/if}
                    {/if}
                {/foreach}
                {$BuildPercentage = ($CompletedCount / {$SelectedCategory.achievements_in_category}) * 100}
                <h3 class="category">{$SelectedCategory.name}</h3>
                <div class="profile-progress border-4" onmouseover="Tooltip.show(this, &#39;{$PointsCount} / {$SelectedCategory.points_for_category} {#Profile_Achievements_Points#}&#39;, { location: &#39;middleRight&#39; });">
                    <div class="bar border-4 hover" style="width: {$BuildPercentage|string_format:"%.2f"}%"></div>
                    <div class="bar-contents">
                        {$CompletedCount} / {$SelectedCategory.achievements_in_category} ({$BuildPercentage|string_format:"%.2f"}%)
                    </div>
                </div>
                {foreach $CompletedAchievements as $Achievement}
                    {if $SelectedCategory.id == $Achievement.category}
                        {if $Achievement.parentAchievement != '-1'}
                            <li class="achievement" data-href="#{$Achievement.parentAchievement}:{$Achievement.category}:a{$Achievement.achievement}" data-id="{$Achievement.achievement}">
                                {else}
                            <li class="achievement" data-href="#{$Achievement.category}:a{$Achievement.achievement}">
                        {/if}
                        <p>
                            <strong>{$Achievement.name}</strong>
                            <span>{$Achievement.description}</span>
                        </p>
                        <a class="fansite-link" data-fansite="achievement|{$Achievement.achievement}" href="javascript:;"></a>
                        <span class="icon-frame frame-50"><img alt="" height="50" src="/Templates/{$Template}/images/icons/large/{$Achievement.iconname|strtolower}.jpg"width="50"></span>

                        <div class="points-big border-8">
                            <strong>{$Achievement.points}</strong> <span class="date">{$Achievement.date|date_format:"%d/%m/%Y"}</span>
                        </div>
                        </li>
                        {append 'CompletedAchievementsArray' $Achievement.achievement}
                    {/if}
                {/foreach}
                {foreach $IncompleteAchievements as $Incomplete}
                    {if is_array($CompletedAchievementsArray) && !in_array($Incomplete.id, $CompletedAchievementsArray)}
                        <li class="achievement locked " data-id="{$Incomplete.id}" data-href="#{$Incomplete.category}:a{$Incomplete.id}">
                            <p>
                                <strong>{$Incomplete.name}</strong>
                                <span>{$Incomplete.description}</span>
                            </p>
                            <a href="javascript:;" data-fansite="achievement|{$Incomplete.id}" class="fansite-link "></a>
                            <span class="icon-frame frame-50 ">
                                <img src="/Templates/{$Template}/images/icons/large/{$Incomplete.iconname}.jpg" alt="" width="50" height="50" />
                            </span>
                            <div class="points-big border-8">
                                <strong>{$Incomplete.points}</strong>
                            </div>
                        </li>
                    {else}
                        <li class="achievement locked " data-id="{$Incomplete.id}" data-href="#{$Incomplete.category}:a{$Incomplete.id}">
                            <p>
                                <strong>{$Incomplete.name}</strong>
                                <span>{$Incomplete.description}</span>
                            </p>
                            <a href="javascript:;" data-fansite="achievement|{$Incomplete.id}" class="fansite-link "></a>
                            <span class="icon-frame frame-50 ">
                                <img src="/Templates/{$Template}/images/icons/large/{$Incomplete.iconname}.jpg" alt="" width="50" height="50" />
                            </span>
                            <div class="points-big border-8">
                                <strong>{$Incomplete.points}</strong>
                            </div>
                        </li>
                    {/if}
                {/foreach}
            {else}
                Selected Category: {$SelectedCategory.id}<br />
                Current Category: {$Category}<br /><br/>
            {/if}
        {/foreach}
    </div>
</div>