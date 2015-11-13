{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a> >
                    <a href="/admin/dashboard/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Title#}</span>
                    </a> >
                    <a href="/admin/shop/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop#}</span>
                    </a> >
                    <a href="/admin/shop/edit-item" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop_DeleteItem#}</span>
                    </a>
                </h2>
                <br />
                <h3 class="headline">{#Administrator_Shop_DeleteItem#}</h3>
                <h2 class="subcategory">{#Administrator_Shop_Delete_Item_Subheader#}</h2>
            </div>
            <div class="dashboard service">
                <div class="primary">
                    <div class="service-wrapper">
                        <div class="service-info">
                            <div class="service-tag">
                                <div class="service-tag-contents border-3">
                                    <div class="service-tag-description">
                                        <span class="character-message caption">{#Administrator_Shop_Delete_Item_SideTitle#}</span>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            </div>
                        </div>
                        <div class="service-form">
                            <div class="character-list">
                                <ul id="character-list">
                                    {foreach $ItemsList as $Item}
                                        <a href="/admin/shop/delete-item-complete/?itemid={$Item.short_code}" class="character-link">
                                            <li class="character border-4" id="Item{$Item.id}:{$Item.item_type}:{$Item.price}">
                                                <div class="character-icon wow-portrait-64-80 glow-shadow-3">
                                                    {if $Item.item_type == 1}
                                                        <img src="/Templates/{$Template}/images/icons/shop_service_icon.jpg" width="64" height="64" alt="" />
                                                    {elseif $Item.item_type == 2}
                                                        <img src="/Templates/{$Template}/images/icons/shop_item_icon.jpg" width="64" height="64" alt="" />
                                                    {elseif $Item.item_type == 3}
                                                        <img src="/Templates/{$Template}/images/icons/shop_mount_icon.jpg" width="64" height="64" alt="" />
                                                    {elseif $Item.item_type == 4}
                                                        <img src="/Templates/{$Template}/images/icons/shop_wallet_icon.jpg" width="64" height="64" alt="" />
                                                    {elseif $Item.item_type == 5}
                                                        <img src="/Templates/{$Template}/images/icons/shop_pet_icon.jpg" width="64" height="64" alt="" />
                                                    {/if}
                                                </div>
                                                <div class="character-description" style="width: 70%;">
                                            <span class="character-name caption">
                                                {$Item.item_name}
                                            </span>
                                            <span class="character-class">
                                            {$Item.category_name}<br />
                                            <strong>{#Administrator_Shop_Add_Item_Price#}:</strong> {$Item.price} USD
                                            </span>
                                                </div>
                                            </li>
                                        </a>
                                    {/foreach}
                                </ul>
                            </div>
                            <div id="error-container" style="display: none;"></div>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='account/account_footer.tpl'}