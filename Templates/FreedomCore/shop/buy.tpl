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
                <h1 class="heading-2">
                    {#Shop_Available_Accounts#}
                </h1>
                <span id="NoAccountSelected" style="color: red"></span>
                <form action="/shop/pay-{$ItemData.short_code}" method="post">
                    <div class="purchase-selection">
                        <br />
                        <ul class="account-list unstyled" id="eligible-account-list">
                            {foreach $Accounts as $Account}
                                <li class="account">
                                    <label class="radio-label">
                                        <input type="radio" name="gameAccountIds" value="{$Account.id}"/>
                                        <div class="summary">
                                            <div class="heading heading-4">
                                                <span class="game-label">WoW{$Account.id}</span>: <span class="game-box-level">{$Account.expansion_name}</span>
                                            </div>
                                            <div class="description">
                                        <span class="last-played">
                                        {#Shop_Last_Login#}
                                            <time datetime="{$Account.last_login}" title="{$Account.last_login}">{$Account.last_login|relative_date}</time>
                                        </span>
                                            </div>
                                        </div>
                                        <div class="message"></div>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                    <div class="purchase-actions account-selection-actions">
                        <div class="grid-100 buttons">
                            <button type="submit" class="btn btn-primary btn-wide" onclick="return CheckIfAccountSelected();" >{#Shop_Continue#}</button>
                            <a class="cancel purchase-cancel btn" onclick="history.go(-1);" tabindex="1">
                                {#Shop_Cancel#}
                            </a>
                        </div>
                    </div>
                    <script>
                        function CheckIfAccountSelected()
                        {
                            if (!$("input[name='gameAccountIds']:checked").val())
                            {
                                $("#NoAccountSelected").empty();
                                $("#NoAccountSelected").html('{#Shop_No_Account_Selected#}');
                                return false;
                            }
                            else
                                return true;
                        }
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
{include file = 'shop/footer.tpl'}