{foreach $Inventory as $Item}
    {if isset($Item.site.side) && $Item.site.side == 'right'}
        {if $Item.site.position == 1}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-10 slot-align-right" style=" top: 0px; right: 0px;">
        {elseif $Item.site.position == 2}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-6 slot-align-right" style=" top: 58px; right: 0px;">
        {elseif $Item.site.position == 3}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-7 slot-align-right" style=" top: 116px; right: 0px;">
        {elseif $Item.site.position == 4}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-8 slot-align-right" style=" top: 174px; right: 0px;">
        {elseif $Item.site.position == 5}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-11 slot-align-right" style=" top: 232px; right: 0px;">
        {elseif $Item.site.position == 6}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-11 slot-align-right" style=" top: 290px; right: 0px;">
        {elseif $Item.site.position == 7}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-12 slot-align-right" style=" top: 348px; right: 0px;">
        {elseif $Item.site.position == 8}
        <div data-id="{$Item.data.InventoryType}" data-type="{$Item.data.InventoryType}" class="slot slot-12 slot-align-right" style=" top: 406px; right: 0px;">
        {/if}
            <div class="slot-inner">
            {if isset($Item.data.entry)}
                <div class="slot-contents">
                    <a href="/item/{$Item.data.entry}" class="item" data-item="{$Item.data.entry}">
                        <img src="/Templates/{$Template}/images/icons/large/{$Item.data.icon}.jpg" alt="" />
                        <span class="frame"></span>
                    </a>
                    <div class="details">
                        <span class="name-shadow">{$Item.data.name}</span>
                        <span class="name color-q{$Item.data.Quality}">
                            <a href="/item/{$Item.data.entry}" data-item="{$Item.data.entry}">{$Item.data.name}</a>
                        </span>
                        {include file='parts/profile_viewer_item_enchantment_part.tpl'}
                        <span class="level">{$Item.data.ItemLevel}</span>
                        {include file='parts/profile_viewer_item_sockets_part.tpl'}
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
{/foreach}