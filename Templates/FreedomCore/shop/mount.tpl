{include file = 'shop/header.tpl'}
<div class="body-content">
    <div class="product-container" id="pageTop">
        <div class="grid-container">
            <div class="grid-50 app-grid-100 buybox no-logo">
                <div  class="product-badge-and-title">
                    <div class="product-badge-container">
                        <i class="product-badge wow-product-badge"></i>
                    </div>
                    <div class="product-title-and-type">
                        <h1 class="product-title"> {$ItemData.item_name} </h1>
                        <h2 class="heading-6 product-type">{str_replace(':', '', $ItemData.category_desc)}</h2>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="product-single">
                        <span class="heading-1 product-priceline">
                            <span class="product-price ">
                                <span class="currency-code">
                                    USD
                                </span> 
                                {$ItemData.price}.00
                            </span>
                        </span>
                    <div class="product-actions" data-bnet-shop="product-action-holder">
                        <a class="btn btn-large btn-primary" href="/shop/buy-{$ItemData.short_code}" tabindex="1" data-gtm-product-name="EU World of Warcraft® In-Game Mount:{$ItemData.item_name}">
                            {#Shop_Buy_Now#}
                        </a>
                    </div>
                </div>
                <div class="product-requirements" id="product-requirements">
                    <ul>
                        <li>
                            {#Shop_Requires#} World of Warcraft
                        </li>
                        <li>
                            <a href="/account/management/redeem-code" data-external="sso">
                                {#Shop_Redeem_Code#}
                                <i class="icon-blue icon-external-link"></i>
                            </a>
                        </li>
                        <li>{#Shop_All_Tax_Included#}</li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <script>
        //<![CDATA[
        var Msg = Msg || {},
        disablePurchaseAndMedia = false;
        Msg.productSlug = "mount-{$ItemData.item_name}";
        $(function() {
            $("#featureMediaLightbox").mediaLightbox();
        });
        (function() {
            var tag = document.createElement("script");
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName("script")[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        })();
        //]]>
    </script>
</div>


{include file = 'shop/footer.tpl'}