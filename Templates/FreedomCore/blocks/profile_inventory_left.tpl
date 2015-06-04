{foreach $Inventory as $Item}
{if is_array($Item)}
    {if $Item.slot == 0 || $Item.slot == 1 || $Item.slot == 2 || $Item.slot == 14 || $Item.slot == 4 || $Item.slot == 3 || $Item.slot == 18 || $Item.slot == 8}
        {if $Item.slot == 0}
            <div data-id="{$Item.slot}" data-type="1" class="slot slot-1" style=" left: 0px; top: 0px;">
        {elseif $Item.slot == 1}
            <div data-id="{$Item.slot}" data-type="2" class="slot slot-2" style=" left: 0px; top: 58px;">
        {elseif $Item.slot == 2}
            <div data-id="{$Item.slot}" data-type="3" class="slot slot-3" style=" left: 0px; top: 116px;">
        {elseif $Item.slot == 14}
            <div data-id="{$Item.slot}" data-type="16" class="slot slot-16" style=" left: 0px; top: 174px;">
        {elseif $Item.slot == 4}
            <div data-id="{$Item.slot}" data-type="5" class="slot slot-5" style=" left: 0px; top: 232px;">
        {elseif $Item.slot == 3}
            <div data-id="{$Item.slot}" data-type="4" class="slot slot-4" style=" left: 0px; top: 290px;">
        {elseif $Item.slot == 18}
            <div data-id="{$Item.slot}" data-type="19" class="slot slot-19" style=" left: 0px; top: 348px;">
        {elseif $Item.slot == 8}
            <div data-id="{$Item.slot}" data-type="9" class="slot slot-9" style=" left: 0px; top: 406px;">
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
