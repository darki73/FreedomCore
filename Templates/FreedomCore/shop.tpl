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
                        <li>
                            {foreach $SidebarItems as $Sidebar}
                                {if !empty($Sidebar)}
                                    {assign 'ArrayKeyID' $Sidebar|key}
                                    {assign 'ArrayKeys' $SidebarItems|array_keys}
                                    <a href="/shop/?categories={$ArrayKeys.$ArrayKeyID}" class="checkbox-label">
                                        <span class="input-checkbox "></span>
                                        <span class="">
                                            <span class="filter-text">
                                                {$ArrayKeys.$ArrayKeyID|ucfirst}
                                            </span>
                                            <span class="count">
                                                ({$Sidebar|count})
                                            </span>
                                        </span>
                                    </a>
                                {/if}
                            {/foreach}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="browse-column main">
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
            </div>
        </div>
        <div class="grid-100 banner-spacer"></div>
    </div>
</div>

{include file = 'shop/footer.tpl'}