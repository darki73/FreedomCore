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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/shop/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/shop/delete-item" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop_DeleteItem#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">

        </div>
    </div>
</div>
{include file='footer.tpl'}