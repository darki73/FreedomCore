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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/localization/{$LanguageData.LanguageLink}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$LanguageData.LanguageName}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/localization/{$LanguageData.LanguageLink}/{$LanguageData.FileLink}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$LanguageData.FileName}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="wod-no-banner"></div>
            <div id="wiki" class="wiki directory wiki-index">
                <div class="panel free-paid-services">
                    <div class="option" style="float:right">
                        <ul class="ui-pagination">
                            {for $i = 0; $i < $Pages; $i++}
                                <li {if $i == $CurrentPage}class="current"{/if}>
                                    <a href="/admin/localization/{$LanguageData.LanguageLink}/{$LanguageData.FileLink}&amp;page={$i}" data-pagenum="{$i}">
                                        <span>{$i}</span>
                                    </a>
                                </li>
                            {/for}
                        </ul>
                    </div>
                    <br /><br />
                    <br /><br />
                    <br /><br />
                    <div class="text-wrapper">
                    {foreach $Lines.$CurrentPage as $Line}
                        {if isset($Line.1)}
                            <h2>{$Line.0}</h2>
                            <div class="input-wrapper">
                                <textarea class="input textarea" name="{$Line.0}" id="{$Line.0}" rows="3" cols="130">{$Line.1}</textarea>
                            </div>
                        {/if}
                    {/foreach}
                        <br />
                        <div class="comments-action">
                            <button class="ui-button button1 comment-submit" onclick="return Localization.update('{$LanguageData.LanguageLink}', '{$LanguageData.FileLink|replace:'edit_':'update_'}');" type="button">
                                <span class="button-left">
                                    <span class="button-right">
                                        Update Data
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}