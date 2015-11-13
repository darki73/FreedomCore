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
                    <a href="/media/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Media#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="media-content">
                <div id="media-index">
                    {if $MediaData.videos != false}
                        <div class="media-index-section float-left">
                            <a class="gallery-title videos" href="/media/videos/">
                            <span class="view-all">
                                <span class="arrow"></span>
                                {#Media_All_Videos#}
                            </span>
                                <span class="gallery-icon"></span>
                                {#Media_Videos#}
                                <span class="total">({$MediaData.videos.count})</span>
                            </a>
                            <div class="section-content">
                                {assign "Iterations" "0"}
                                {foreach $MediaData.videos.items as $Video}
                                    <a href="/media/videos/{$Video.slug}" class="thumb-wrapper video-thumb-wrapper">
                                        <span class="video-info">
                                            <span class="video-title">{$Video.name}</span>
                                            <span class="video-desc">{#Media_ReadMore#}</span>
                                            <span class="date-added">{#Media_PublishedOn#} {$Video.publish_date|date_format:"d/m/Y"}</span>
                                        </span>
                                        <span class="thumb-bg" style="background-image:url({$MediaData.videos.data.folder}{$Video.thumbnail})">
                                            <span class="thumb-frame"></span>
                                        </span>
                                    </a>
                                    {$Iterations = $Iterations + 1}
                                    {if $Iterations == 2}
                                        {break}
                                    {/if}
                                {/foreach}
                                <span class="clear"><!-- --></span>
                            </div>
                            <span class="clear"><!-- --></span>
                        </div>
                    {/if}
                    {if $MediaData.screenshots != false}
                        <div class="media-index-section float-left">
                            <a class="gallery-title screenshots" href="/media/screenshots/">
                            <span class="view-all">
                                <span class="arrow"></span>
                                {#Media_All_Screenshots#}
                            </span>
                                <span class="gallery-icon"></span>
                                {#Media_Screenshots#}
                                <span class="total">({$MediaData.screenshots.count})</span>
                            </a>

                            <span class="clear"><!-- --></span>
                        </div>
                    {/if}

                    <div style="display:none" id="media-preload-container"></div>
                </div>
            </div>
        <div>
    </div>
</div>
{include file="footer.tpl"}