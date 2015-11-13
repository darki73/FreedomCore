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
                    <a href="/admin/articles/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Articles#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki directory wiki-index">
                <div class="panel free-paid-services">
                    <div id="free-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Articles#}
                        </h2>
                        <ul>
                            <li>
                                <a href="/admin/articles/add" class="paid-services-name-change">
                                    <span>{#Administrator_Articles#}</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/users/" class="paid-services-race-change">
                                    <span>{#Administrator_UserManagement#}</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/patchnotes/" class="paid-services-character-transfer">
                                    <span>{#Administrator_PatchNotes#}</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/security/" class="free-services-security">
                                    <span>{#Administrator_Antivirus#}</span>
                                </a>
                            </li>
                            <li>
                                <hr>
                            </li>
                            <li>
                                <a href="/admin/localization/" class="paid-services-character-transfer">
                                    <span>{#Administrator_Localization#}</span>
                                </a>
                            </li>
                            <hr>
                            <li>
                                <a href="/admin/shop/" class="free-services-rewards-visa">
                                    <span>
                                        {#Administrator_Shop#}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}