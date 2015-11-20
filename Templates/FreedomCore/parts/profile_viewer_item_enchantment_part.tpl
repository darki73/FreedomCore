{if $Item.data.enchanted}
    {foreach $Item.enchantments as $Enchantment}
        {if $Enchantment.is_spell}
            <div class="enchant color-q2">
                <a {if $Enchantment.create_tooltip}href="/item/{$Enchantment.enchant_id}" data-item="{$Enchantment.enchant_id}"{else}href="#"{/if}>{$Enchantment.name}</a>
            </div>
            {break}
        {/if}
    {/foreach}
{/if}