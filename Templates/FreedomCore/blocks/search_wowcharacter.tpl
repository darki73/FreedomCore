<div class="view-table">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th class=" first-child">
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=subject&amp;dir=a" class="sort-link">
                            <span class="arrow">
                                {#Community_Name#}
                            </span>
                        </a>
                    </th>
                    <th>
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=level&amp;dir=a" class="sort-link">
			                <span class="arrow">
                                {#Character_Level#}
                            </span>
                        </a>
                    </th>
                    <th>
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=raceId&amp;dir=a" class="sort-link">
			                <span class="arrow">
                                {#Race#}
                            </span>
                        </a>
                    </th>
                    <th >
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=classId&amp;dir=a" class="sort-link">
			                <span class="arrow">
                                {#Class#}
                            </span>
                        </a>
                    </th>
                    <th>
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=factionId&amp;dir=a" class="sort-link">
			                <span class="arrow">
                                {#Character_Faction#}
                            </span>
                        </a>
                    </th>
                    <th>
                        <a href="?f=wowcharacter&amp;q={$SearchedFor}&amp;sort=guildName&amp;dir=a" class="sort-link">
			                <span class="arrow">
                                {#Community_Guild#}
                            </span>
                        </a>
                    </th>
                    <th>
                        <a class="sort-link">
                            <span class="arrow">
                            {#Character_Status#}
                        </span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
            {if $SearchResults.charactersfound > 0}
                {foreach from=$SearchResults.characters item=Character key=i}
                    <tr class="row{if $i%2==1}2{else}1{/if}">
                        <td>
                            <a href="/character/{$Character.name}/" class="item-link color-c{$Character.class}">
						<span class="icon-frame frame-18">
							<img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" alt="" width="18" height="18" />
						</span>
                                <strong>{$Character.name}</strong>
                            </a>
                        </td>
                        <td class="align-center">
                            {$Character.level}
                        </td>
                        <td class="align-center">
                        <span class="icon-frame frame-14 " data-tooltip="{$Character.race_name}">
                            <img src="/Templates/{$Template}/images/icons/small/race_{$Character.race}_{$Character.gender}.jpg" alt="" width="14" height="14" />
                        </span>
                        </td>
                        <td class="align-center">
                        <span class="icon-frame frame-14 " data-tooltip="{$Character.class_name}">
                            <img src="/Templates/{$Template}/images/icons/small/class_{$Character.class}.jpg" alt="" width="14" height="14" />
                        </span>
                        </td>
                        <td class="align-center">
                        <span class="icon-frame frame-14 " data-tooltip="{$Character.sidetranslation}">
                            <img src="/Templates/{$Template}/images/icons/small/faction_{$Character.side}.jpg" alt="" width="14" height="14" />
                        </span>
                        </td>
                        <td>
                            <a href="/guild/{$Character.guild}/" class="sublink">{$Character.guild}</a>
                        </td>
                        <td class="align-center">
                            {if $Character.online == 1}
                                <font color="green">{#Character_Online#}</font>
                            {else}
                                <font color="red">{#Character_Offline#}</font>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/if}
            </tbody>
        </table>
    </div>
</div>