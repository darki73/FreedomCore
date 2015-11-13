{include file = 'shop/header.tpl'}
<div class="body-content">
    <div class="grid-container browse game wow">
        <div  class="logo-banner">
            <img class="family-logo" src="/Templates/{$Template}/images/logos/logo-family-wow.png" alt="" />
        </div>
        <div class="browse-container">
            <div class="browse-column sidebar">
                <div class="sidebar-content">
                    <h3 class="heading-6 filter-by-type">
                        {#Shop_Filters#}
                    </h3>
                    <ul class="filter-list nav nav-list">
                        {foreach from=$SidebarItems item=Sidebar key=i}
                            {if !empty($Sidebar)}
                                <li>
                                    {if isset($DisplayCategory) && $DisplayCategory == $i}
                                    <a href="/shop/" class="checkbox-label">
                                            <span class="input-checkbox checked"></span>
                                    {else}
                                    <a href="/shop/?categories={$i}" class="checkbox-label">
                                            <span class="input-checkbox "></span>
                                    {/if}
                                            <span class="">
                                                <span class="filter-text">
                                                    {$i|ucfirst}
                                                </span>
                                                <span class="count">
                                                    ({$Sidebar|count})
                                                </span>
                                            </span>
                                    </a>
                                </li>
                            {/if}
                        {/foreach}
                    </ul>
                </div>
            </div>
            <div class="browse-column main">
            {if !isset($DisplayCategory)}
                {if !empty($SidebarItems.mounts)}
                    <h2 class="filter-title">Mounts</h2>
                    <ul class="product-card-container thumbnails">
                        {foreach $SidebarItems.mounts as $Mount}
                            <li>
                                <a class="product-link" href="/shop/mount-{$Mount.short_code}" tabindex="1" data-gtm-click="productCardClick" data-gtm-product-name="In-Game Mount: {$Mount.item_name}">
                                    <div class="cover"></div>
                                    <div class="thumbnail">
                                        <img src="/Templates/{$Template}/images/shop/mounts/{$Mount.item_shop_icon}_home.jpg" alt="{$Mount.item_name}" />
                                        <div class="product-card-info">
                                            <h3 class="product-name">{$Mount.item_name}</h3>
                                            <p class="product-price ">
                                                USD {$Mount.price}.00
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
                {if !empty($SidebarItems.items)}
                    <h2 class="filter-title">Items</h2>
                    <ul class="product-card-container thumbnails">
                        {foreach $SidebarItems.items as $Item}
                            <li>
                                <a class="product-link" href="/shop/item-{$Item.short_code}" tabindex="1" data-gtm-click="productCardClick" data-gtm-product-name="In-Game Item: {$Item.item_name}">
                                    <div class="cover"></div>
                                    <div class="thumbnail">
                                        <img src="/Templates/{$Template}/images/shop/items/{$Item.item_shop_icon}_home.jpg" alt="{$Item.item_name}" />
                                        <div class="product-card-info">
                                            <h3 class="product-name">{$Item.item_name}</h3>
                                            <p class="product-price ">
                                                USD {$Item.price}.00
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            {else}
                <h2 class="filter-title">{$DisplayCategory|ucfirst}</h2>
                <ul class="product-card-container thumbnails">
                    {foreach $SidebarItems.$DisplayCategory as $Item}
                        <li>
                            <a class="product-link" href="/shop/{substr($DisplayCategory,0, -1)}-{$Item.short_code}" tabindex="1" data-gtm-click="productCardClick" data-gtm-product-name="In-Game Item: {$Item.item_name}">
                                <div class="cover"></div>
                                <div class="thumbnail">
                                    <img src="/Templates/{$Template}/images/shop/{$DisplayCategory}/{$Item.item_shop_icon}_home.jpg" alt="{$Item.item_name}" />
                                    <div class="product-card-info">
                                        <h3 class="product-name">{$Item.item_name}</h3>
                                        <p class="product-price ">
                                            USD {$Item.price}.00
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            {/if}
            </div>
        </div>
        <div class="grid-100 banner-spacer"></div>
    </div>
</div>

{include file = 'shop/footer.tpl'}