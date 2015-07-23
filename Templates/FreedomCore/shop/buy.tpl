{include file = 'shop/header.tpl'}
<div class="body-content">
    <div class="grid-container purchase-content">
        <div class="purchase-container">
            <div class="purchase-column grid-25 grid-parent">
                <div class="purchase-sidebar">
                    <div class="product-summary clearfix">
                        <div class="product-image thumbnail">
                            <img src="/Templates/{$Template}/images/shop/{$ItemData.category}/{$ItemData.item_shop_icon}_home.jpg" alt="World of Warcraft® In-Game Mount: {$ItemData.item_name}" title="" />
                        </div>
                        <div class="product-name-group">
                            <h6 class="product-desc-label">{#Shop_You_Are_Purchasing#}</h6>
                            <p class="product-name">
                                {$ItemData.category_desc} {$ItemData.item_name}
                            </p>
                        </div>
                        <div class="product-summary-information" id="product-summary-information">
                            <div class="product-price-group">
                                <h6 class="product-desc-label">{#Shop_Price#}</h6>
                                <p class="mp-product-price">
                                    <span class="currency-code">USD</span> {$ItemData.price}.00 </p>
                            </div>
                            <div class="product-details-group">
                                <h6 class="product-desc-label">{#Shop_Details#}</h6>
                                <ul class="product-features">
                                    <li>
                                        <i class="icon-shopping-cart icon-gray"></i>
                                        {#Shop_Digital_Purchase#}
                                    </li>
                                    <li>
                                        <i class="icon-globe-alt icon-gray"></i>
                                        {#Shop_Worldwide#}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="purchase-column grid-75 grid-parent">

            </div>
        </div>
    </div>
</div>
{include file = 'shop/footer.tpl'}