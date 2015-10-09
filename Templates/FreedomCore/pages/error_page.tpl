{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" itemprop="url" class="">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="server-error">
                <h2 class="http">{$Error.code}</h2>
                <h3>{$Error.error_type}</h3>
                <p>{$Error.error_description}</p>


                <!-- http : {$Error.code} -->
            </div>

        </div>
    </div>
</div>
{include file="footer.tpl"}