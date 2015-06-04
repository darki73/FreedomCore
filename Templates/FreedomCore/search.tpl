{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/search/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Search#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="search">
                <div class="search-right">
                    <div class="search-header">
                        <form action="/search" method="get" class="search-form">
                            <div>
                                <input id="search-page-field" type="text" name="q" maxlength="200" tabindex="2" value="" />



                                <button class="ui-button button1" type="submit"><span class="button-left"><span class="button-right">{#Search#}</span></span></button>
                            </div>
                        </form>
                    </div>

                    <div class="no-results">
                        <h2 class="subheader-2">{#Search_New#}</h2>
                        <h3 class="header-3">{#Search_Like#}</h3>

                        <ul>
                            <li>{#Search_Like_First#}</li>
                            <li>{#Search_Like_Second#}</li>
                            <li>{#Search_Like_Third#}</li>
                        </ul>
                    </div>

                </div>

                <div class="search-left">
                    <div class="search-header">
                        <h2 class="header-2">{#Search#}</h2>
                    </div>

                </div>

                <span class="clear"><!-- --></span>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl"}