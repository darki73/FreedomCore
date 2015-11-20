{if !isset($ItemInfoPage)}

<div class="wiki-tooltip">
		<span  class="icon-frame frame-56 " style='background-image: url("/Templates/{$Template}/images/icons/large/{$Item.icon}.jpg");'>
		</span>
    <h3 class="color-q{$Item.Quality}">{$Item.name}</h3>
    {/if}
    <ul class="item-specs" style="margin: 0">
        {if $Item.Quality != 5}
            {if $Item.ItemLevel == 277 || $Item.ItemLevel == 284}
                <li style="color:#00ff00">{#Item_Heroic#}</li>
            {/if}
        {/if}
        <li class="color-tooltip-yellow">{#Item_ItemLevel#} {$Item.ItemLevel}</li>
        {if $Item.bonding != 0}
            <li>{$Item.bond_translation}</li>
        {/if}
        {if $Item.InventoryType != 0}
            <li>
                {if $Item.subclass.subclass != 0}
                    <span class="float-right">{$Item.subclass.translation}</span>
                {/if}
                {$Item.it_translation}
            </li>
        {/if}
        {if $Item.delay != 0}
            <li>
                <span class="float-right">{#Item_Attack_Speed#} {($Item.delay/1000)|string_format:"%.2f"}</span>
                {if $Item.dmg_min1 != 0}
                    {$Item.dmg_min1} - {$Item.dmg_max1}
                    {$DPS = ($Item.dmg_min1 + $Item.dmg_max1)/2/($Item.delay/1000)}
                {elseif $Item.dmg_min2 !=0}
                    {$Item.dmg_min2} - {$Item.dmg_max2}
                    {$DPS = ($Item.dmg_min2 + $Item.dmg_max2)/2/($Item.delay/1000)}
                {/if}
                {if $Item.dmg_min1 != 0 && $Item.dmg_max1 != 0}
                    {#Item_Damage#}
                {/if}
            </li>
        {/if}
        {if isset($DPS)}
            <li>
                ({$DPS|string_format:"%.2f"} {#Item_DPS#})
            </li>
        {/if}
        {for $i = 1; $i <=10; $i++}
            {$StatName = 'stat_type'|cat:$i}
            {$StatValue = 'stat_value'|cat:$i}
            {$StatTranslation = 'stat_translation'|cat:$i}
            {if isset($Item.$StatName)}
                {if $Item.$StatName != 0}
                    {if $i < 3}
                        <li id="stat-{$Item.$StatName}" >
                            +{$Item.$StatValue} {$Item.$StatTranslation}
                        </li>
                    {else}
                        <li id="stat-{$Item.$StatName}" class="color-tooltip-green">
                            +{$Item.$StatValue} {$Item.$StatTranslation}
                        </li>
                    {/if}
                {/if}
            {/if}
        {/for}
        {if $Item.socketColor_1 != 0}
            <li>
                <ul class="item-specs">
                    {for $i = 1; $i <= 3; $i++}
                        {$SocketColor = 'socketColor_'|cat:$i}
                        {$Socket = 'socket'|cat:$i}
                        {if $Item.$SocketColor != 0}
                            <li class="color-d{$Item.$Socket.subclass}">
                        <span class="icon-socket socket-{$Item.$SocketColor}">
                                <span class="empty"></span>
                                <span class="frame"></span>
                        </span>
                                {$Item.$Socket.translation}
                                <span class="clear"><!-- --></span>
                            </li>
                        {/if}
                    {/for}
                    {if $Item.socketBonus != 0}
                        <li class="color-d4">{#Item_On_Socket_Match#}  {if $Item.socketBonusDescription != ''}{$Item.socketBonusDescription}{else}{$Item.socketBonus}{/if}</li>
                    {/if}
                </ul>
            </li>
        {/if}
        {if $Item.spellid_1 != 0}
            {for $i = 1; $i <= 3; $i++}
                {$SpellID = 'spellid_'|cat:$i}
                {$SpellData = 'spell_data'|cat:$i}
                {$SpellTranslation = 'spt_translation'|cat:$i}
                {if isset($Item.$SpellTranslation)}
                    <li class="color-q2 item-spec-group">
                        {if !isset($ItemInfoPage)}
                            {$Item.$SpellTranslation} {$Item.$SpellData.Description}
                        {else}
                            {if $Item.$SpellData.Description != ''}
                                <span class="tip" data-spell="{$Item.$SpellData.SpellID}" data-spell-item="{$Item.entry}">
                                    {$Item.$SpellTranslation}
                                    {$Item.$SpellData.Description}
                                </span>
                            {/if}
                        {/if}
                    </li>
                {/if}
            {/for}
        {/if}
        {if !empty($Item.itemsetinfo)}
            {if $Item.itemsetinfo.itemsinset > 0}
                <li>
                    <ul class="item-specs">
                        <li class="color-tooltip-yellow">{$Item.itemsetinfo.name_loc0} (0/{$Item.itemsetinfo.itemsinset})</li>
                        {for $i = 1; $i <= 10; $i++}
                            {$ItemName = 'item'|cat:$i}
                            {if $Item.itemsetinfo.$ItemName != 0}
                                <li class="indent">
                                    <a class="color-d4 tip" href="/item/{$Item.itemsetinfo.$ItemName.entry}" data-item="{$Item.itemsetinfo.$ItemName.entry}">
                                        {$Item.itemsetinfo.$ItemName.name}
                                    </a>
                                </li>
                            {/if}
                        {/for}
                        <li class="indent-top"> </li>
                        {for $i = 0; $i <= 7; $i++}
                            {$SetBonus = 'setbonus'|cat:$i}
                            {if isset($Item.itemsetinfo.$SetBonus)}
                                <li class="color-d4">
                                    {$Item.itemsetinfo.$SetBonus}
                                </li>
                            {/if}
                        {/for}
                    </ul>
                </li>
            {/if}
        {/if}
        {if isset($Item.gem_bonus)}
            <li>{$Item.gem_bonus}</li>
        {/if}
        {if $Item.class.class != 15 && $Item.subclass.subclass != 5}
            {if $Item.description != ''}
                <li class="color-tooltip-yellow">
                    "{$Item.description}"
                </li>
            {/if}
        {/if}
        <li>
            <ul class="item-specs">
                {if $Item.MaxDurability != 0}
                    <li>{#Item_Durability#} {$Item.MaxDurability} / {$Item.MaxDurability}</li>
                {/if}
                {if $Item.RequiredLevel != 0}
                    <li>{#Item_Required_Level#} {$Item.RequiredLevel}</li>
                {/if}
                {if !empty($Item.SellPrice)}
                    <li>
                        {#Item_SellPrice#}:
                        {if isset($Item.SellPrice.gold)}
                            <span class="icon-gold">{$Item.SellPrice.gold}</span>
                        {/if}
                        {if isset($Item.SellPrice.silver)}
                            <span class="icon-silver">{$Item.SellPrice.silver}</span>
                        {/if}
                        {if isset($Item.SellPrice.copper)}
                            <span class="icon-copper">{$Item.SellPrice.copper}</span>
                        {/if}
                    </li>
                {/if}
            </ul>
        </li>
    </ul>

    <span class="clear"><!-- --></span>
    {if !isset($ItemInfoPage)}
</div>
{/if}