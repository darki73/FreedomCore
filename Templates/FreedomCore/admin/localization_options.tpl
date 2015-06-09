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
                    <a href="/admin/localization/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Localization#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/localization/{$LanguageData.LanguageLink}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$LanguageData.LanguageName}</span>
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
                            {#Administrator_Localization_Edit#}
                        </h2>
                        <ul>
                            <li>
                                <a href="/admin/localization/{$LanguageData.LanguageLink}/edit_{$LanguageData.LanguageName|strtolower}" class="paid-services-name-change">
                                    <span>{#Administrator_Localization_MainFile#}</span>
                                </a>
                            </li>
                            <li>
                                <hr>
                            </li>
                            {foreach $LanguageData.SubFolderFiles as $File}
                                <li>
                                    <a href="/admin/localization/{$LanguageData.LanguageLink}/edit_{$File.SmallFileName}" class="paid-services-name-change">
                                        <span>{$File.FileName} ({$File.LinesCount} Lines)</span>
                                    </a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}