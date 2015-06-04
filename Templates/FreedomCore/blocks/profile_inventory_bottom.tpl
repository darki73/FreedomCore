{foreach $Inventory as $Item}
{if is_array($Item)}
    {if $Item.slot == 15 || $Item.slot == 16}
    {if $Item.slot == 15}
    <div data-id="{$Item.slot}" data-type="21" class="slot slot-21 slot-align-right " style=" left: 132.5px; bottom: 0px;">
    {elseif $Item.slot == 16}
    <div data-id="{$Item.slot}" data-type="22" class="slot slot-22 " style=" left: 409.5px; bottom: 0px;">
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
