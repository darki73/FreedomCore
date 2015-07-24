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
                                    <li>
                                        <i class="icon-wow icon-gray"></i>
                                        {#Shop_Account_Selected#}{$BuyingFor}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="purchase-column grid-75 grid-parent">
                <h1 class="heading-1 purchase-heading">
                    {#Shop_Payment#}
                    <i class="icon-32-secure icon-32-white"></i>
                </h1>
                <div class="grid-70 suffix-30">
                    {if $User.balance < $ItemData.price}
                        <div class="alert alert-error alert-icon">
                            <p>
                                {#Shop_Not_Enough_Funds#}
                            </p>
                            <p>
                                {#Shop_Top_Up_First#}
                            </p>
                        </div>
                    {/if}
                </div>
                <div class="grid-60 prefix-5 push-35 app-grid-100 app-prefix-0 app-push-0" id="checkout-payment-icons">
                    <span class="control-label">{#Shop_We_Accept#}</span>
                    <div class="controls">
                        <div class="accepted-payment-icons">
                            <span class="icon-24-payment-battlenet-balance">battlenet-balance</span>
                        </div>
                    </div>
                </div>
                <div class="grid-35 pull-65 app-grid-70 app-pull-0" id="checkout-pay-with">
                    <fieldset class="pay-with">
                        <div class="control-group">
                            <label class="control-label" for="payment-option">{#Shop_Payment_Options#}</label>
                            <div class="controls">
                                <div tabindex="1" class="select-box input-block" id="select-box-payment-option" data-select="select" data-original-id="payment-option" data-select-id="select-box-payment-option">
                                    <span class="current">
                                        <i class="icon-payment-battlenet-balance"></i>
                                        <span class="text">
                                            {#Shop_Current_Wallet#}
                                        </span>
                                    </span>
                                    <span class="arrow"></span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="grid-35 suffix-65 app-grid-100 app-suffix-0">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">{#Shop_More_Info#}</label>
                            <span class="uneditable-input input-block saved-payment-info">
                                {#Shop_Balance#} {$User.selected_currency} {$User.balance}.00
                            </span>
                        </div>
                    </fieldset>
                </div>
                <div class="grid-70 suffix-30 app-grid-100 app-suffix-0">
                    <div class="checkout-total" id="checkout-total">
                        <div class="total">
                            <h2 class="heading-6 total-heading">
                                <span id="total-heading">
                                    <span data-tooltips="tap" class="tooltipstered">
                                        {#Shop_Total#}
                                        <span>({#Shop_Inc_Tax#})</span>
                                        <i class="icon-question-circle icon-blue"></i>
                                    </span>
                                </span>
                            </h2>
                            <h3 class="heading-1 total-cost" id="total-cost" data-base-cost="{$ItemData.price}">
                                {$ItemData.price}.00 <span class="currency-code">USD</span> </h3>
                        </div>
                    </div>
                    {if $User.balance >= $ItemData.price}
                        <form action="/shop/complete-{$ItemData.short_code}" method="post">
                            <div class="form-actions purchase-form-actions">
                                <input type="hidden" value="{$BuyingFor}" name="gameAccountIds">
                                <button type="submit" class="btn btn-primary btn-wide" id="payment-submit" data-gtm-id="payment-submit" tabindex="1">
                                    {#Shop_Complete_Payment#}
                                </button>
                                <a id="payment-cancel" class="cancel purchase-cancel btn" onclick="history.go(-2);" tabindex="1">
                                    {#Shop_Cancel#}
                                </a>
                            </div>
                        </form>
                    {else}
                        <a id="payment-cancel" class="cancel purchase-cancel btn" onclick="history.go(-2);" tabindex="1">
                            {#Shop_Cancel#}
                        </a>
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
{include file = 'shop/footer.tpl'}