{foreach $Inventory as $Item}
{if is_array($Item)}
    {if $Item.slot == 9 || $Item.slot == 5 || $Item.slot == 6 || $Item.slot == 7 || $Item.slot == 10 || $Item.slot == 11 || $Item.slot == 12 || $Item.slot == 13}
        {if $Item.slot == 9}
            <div data-id="{$Item.slot}" data-type="10" class="slot slot-10 slot-align-right" style=" top: 0px; right: 0px;">
        {elseif $Item.slot == 5}
            <div data-id="{$Item.slot}" data-type="6" class="slot slot-6 slot-align-right" style=" top: 58px; right: 0px;">
        {elseif $Item.slot == 6}
            <div data-id="{$Item.slot}" data-type="7" class="slot slot-7 slot-align-right" style=" top: 116px; right: 0px;">
        {elseif $Item.slot == 7}
            <div data-id="{$Item.slot}" data-type="8" class="slot slot-8 slot-align-right" style=" top: 174px; right: 0px;">
        {elseif $Item.slot == 10}
            <div data-id="{$Item.slot}" data-type="11" class="slot slot-11 slot-align-right" style=" top: 232px; right: 0px;">
        {elseif $Item.slot == 11}
            <div data-id="{$Item.slot}" data-type="11" class="slot slot-11 slot-align-right" style=" top: 290px; right: 0px;">
        {elseif $Item.slot == 12}
            <div data-id="{$Item.slot}" data-type="12" class="slot slot-12 slot-align-right" style=" top: 348px; right: 0px;">
        {elseif $Item.slot == 13}
            <div data-id="{$Item.slot}" data-type="12" class="slot slot-12 slot-align-right" style=" top: 406px; right: 0px;">
        {/if}
                <div class="slot-inner">
                    {if isset($Item.data)}
                        <div class="slot-contents">
                            <a href="/item/{$Item.itemEntry}" class="item" data-item="{$Item.itemEntry}">
                                <img src="/Templates/{$Template}/images/icons/large/{$Item.data.icon}.jpg" alt="" />
                                <span class="frame"></span>
                            </a>
                            <div class="details">
                                <span class="name-shadow">{$Item.data.name}</span>
                                <span class="name color-q{$Item.data.Quality}">
                                    <a href="/item/{$Item.itemEntry}" data-item="{$Item.itemEntry}">{$Item.data.name}</a>
                                </span>
                                <span class="level">{$Item.data.ItemLevel}</span>
                            </div>
                    </div>
                {else}
                    <div class="slot-contents">
                        <a href="javascript:;" class="empty"><span class="frame"></span></a>
                    </div>
                {/if}
                </div>
            </div>
    {/if}
{/if}
{/foreach}