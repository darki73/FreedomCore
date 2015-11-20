{foreach $Inventory as $Item}
    {if isset($Item.site.side) && $Item.site.side == 'bottom'}
        {if $Item.site.position == 1 || $Item.site.position == 2 || $Item.site.position == 3}
        {if $Item.site.position == 1}
        <div data-id="{$Item.data.InventoryType}" data-type="21" class="slot slot-21 slot-align-right " style=" left: 132.5px; bottom: 0px;">
        {elseif $Item.site.position == 2}
        <div data-id="{$Item.data.InventoryType}" data-type="22" class="slot slot-22 " style=" left: 409.5px; bottom: 0px;">
        {elseif $Item.site.position == 3 && $Inventory.hasOffhand == false}
        <div data-id="{$Item.data.InventoryType}" data-type="23" class="slot slot-22 " style=" left: 409.5px; bottom: 0px;">
        {elseif $Item.site.position == 3 && $Inventory.hasOffhand == true}
            {break}
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
    {/if}
{/foreach}