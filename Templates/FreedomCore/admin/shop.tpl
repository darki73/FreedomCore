{include file='header.tpl'}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/dashboard/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Title#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/shop/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="wod-no-banner"></div>
            <div id="wiki" class="wiki directory wiki-index">
                <div class="panel free-paid-services">
                    <div id="free-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Shop_Management#}
                        </h2>

                        <ul>
                            <li>
                                <a href="/admin/shop/add-item" class="free-service-additem">
                                    <span>
                                        {#Administrator_Shop_AddItem#}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/shop/delete-item" class="free-service-database">
                                    <span>
                                        {#Administrator_Shop_DeleteItem#}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/shop/edit-item" class="free-service-edititem">
                                    <span>
                                        {#Administrator_Shop_EditItem#}
                                    </span>
                                </a>
                            </li>
                            </li>
                        </ul>
                    </div>
                    <div id="paid-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Shop_Stats#}
                        </h2>
                        <ul>
                            <li>
                                <a class="free-service-countitems">
                                    <span>{#Administrator_Shop_Items_Count#}: {$ShopData.count}</span>
                                </a>
                            </li>
                            <li>
                                <a class="free-services-rewards-visa">
                                    <span>
                                        {#Administrator_Shop_Items_Price#}: {$ShopData.total} USD
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="paid-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Shop_Revenue#}
                        </h2>
                        <ul>

                        </ul>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}