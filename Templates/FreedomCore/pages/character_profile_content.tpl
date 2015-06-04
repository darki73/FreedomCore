<div class="profile-contents">
    <div class="summary-top">
        <div class="summary-top-right">
            <ul class="profile-view-options" id="profile-view-options-summary">
                <li {if $PageType == 'advanced' || $Page.type == ''}class="current"{/if}>
                    <a href="/character/{$Character.name}/advanced" rel="np" class="advanced">
                        {#Profile_Character_Advanced#}
                    </a>
                </li>
                <li {if $PageType == 'simple'}class="current"{/if}>
                    <a href="/character/{$Character.name}/simple" rel="np" class="simple">
                        {#Profile_Character_Simple#}
                    </a>
                </li>
            </ul>
            <div class="summary-averageilvl">
                {if $Inventory.EquippedItems == 0}
                    {$CountItemLevel = 0}
                {else}
                    {$CountItemLevel = $Inventory.TotalItemLevel / $Inventory.EquippedItems}
                {/if}
                <div class="rest">
                    {#Profile_Character_Average_Level#}<br />
                    (<span class="equipped">{$CountItemLevel|string_format:"%d"}</span> {#Profile_Character_Equipped_Level#})
                </div>
                <div id="summary-averageilvl-best" class="best tip" data-id="averageilvl">
                    {$CountItemLevel|string_format:"%d"}
                </div>
            </div>
        </div>
        <div class="summary-top-inventory">
            <div id="summary-inventory" class="summary-inventory summary-inventory-advanced">
                <div id="3DModel" style="width: 50%; margin: 0 auto; pointer-events: none;"></div>
                {include file='blocks/profile_inventory_left.tpl'}
                {include file='blocks/profile_inventory_right.tpl'}
                {include file='blocks/profile_inventory_bottom.tpl'}
            </div>
        </div>
    </div>
    <div class="summary-bottom">
        {include file='blocks/profile_summary_right.tpl'}
        {include file='blocks/profile_summary_left.tpl'}
        <span class="clear"><!-- --></span>
        {include file='blocks/profile_sub_content.tpl'}
        <span class="clear"><!-- --></span>
        {include file='blocks/profile_raid_content.tpl'}
        <span class="clear"><!-- --></span>
        <div class="summary-lastupdate">
            {#Character_Last_Update#} {$smarty.now|date_format:"%d/%m/%Y %H:%M:%S"}
        </div>
    </div>
</div>
<span class="clear"><!-- --></span>