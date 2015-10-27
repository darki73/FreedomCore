{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div id="lobby">
                <div id="page-content" class="page-content">
                    {include file = 'admin/admin_sidebar.tpl'}
                    <div id="lobby-games">
                        <h3 class="section-title">{#Administrator_SiteStat#}</h3>
                        <div id="games-list">
                            <ul id="game-list-wow">
                                <li class="border-4" style="background: #ededeb;">
                                    <span class="account-info">
                                        <span class="account-link">
                                            <strong style="font-size: 16px;">
                                                {#Administrator_Users_Registered#}
                                            </strong>
                                            <span class="account-id">
                                                <span class="account-edition">
                                                    <strong style="color: #4e4e4e!important;">{#Administrator_Users_Registered_Today#}</strong>
                                                </span>
                                            </span>
                                            <span class="account-region">
                                                <strong style="color: #4e4e4e!important;">{#Administrator_Users_Registered_Total#}</strong>
                                            </span>
                                        </span>
                                    </span>
                                </li>
                                <li class="border-4" style="background: #ededeb;">
                                    <span class="account-info">
                                        <span class="account-link">
                                            <strong style="font-size: 16px;">
                                                {#Administrator_Shop_Stats#}
                                            </strong>
                                            <span class="account-id">
                                                <span class="account-edition">
                                                    <strong style="color: #4e4e4e!important;">{#Administrator_Shop_Items_Count#}:</strong> {$ShopData.count}
                                                </span>
                                            </span>
                                            <span class="account-region">
                                                <strong style="color: #4e4e4e!important;">{#Administrator_Shop_Items_Price#}:</strong> {$ShopData.total} USD
                                            </span>
                                        </span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='account/account_footer.tpl'}