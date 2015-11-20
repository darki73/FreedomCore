{if isset($Item.site.side) && $Item.site.side == 'left'}
<span class="sockets" style="width: 75px;">
{else}
<span class="sockets">
{/if}
{if $Item.data.socketColor_1 != 0}
    {for $i = 1; $i <= 3; $i++}
        {$SocketColor = 'socketColor_'|cat:$i}
        {$Socket = 'socket'|cat:$i}
        {if $Item.data.$SocketColor != 0}
            {if isset($Item.enchantments.$Socket)}
                <span class="icon-socket socket-{$Item.data.$Socket.css_position}">
                        <a href="/item/{$Item.enchantments.$Socket.enchant_id}" data-item="{$Item.enchantments.$Socket.enchant_id}" class="gem">
                            <img src="/Templates/{$Template}/images/icons/small/{$Item.enchantments.$Socket.icon}.jpg" alt="{$Item.enchantments.$Socket.name}" />
                            <span class="frame"></span>
                        </a>
                </span>
            {else}
                <span class="icon-socket socket-{$Item.data.$Socket.css_position}">
                        <span class="empty"></span>
                        <span class="frame"></span>
                </span>
            {/if}
        {/if}
    {/for}
</span>
{/if}