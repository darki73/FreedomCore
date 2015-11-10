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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/media" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Media#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/media/videos" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Media_Videos#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="media-content">
                <script type="text/javascript">
                    //<![CDATA[
                    var galleryType = "videos";
                    var dataKey = "videos";
                    var viewType = "film-strip";
                    var discussionKeys = [];
                    var discussionSigs = [];
                    var indices = [];
                    var itemPaths = [];
                    var videoData = [];
                    //]]>
                </script>
                <div itemscope="itemscope" itemprop="video" itemtype="http://schema.org/VideoObject">
                    <div class="film-strip-wrapper">
                        <div id="film-strip">
                            <div class="viewport-scrollbar" style="height: 615px;">
                                <div class="track" style="height: 615px;">
                                    <div id="scroll-thumb" class="thumb" style="top: 0px; height: 80px;">
                                        <div class="thumb-bot"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="viewport-content">
                                <div id="film-strip-thumbnails" class="video-thumbnails">
                                    {assign "Iterations" "0"}
                                    {foreach $MediaVideos.items as $Video}
                                        <a id="{$Video.slug}"
                                           data-tooltip="{$Video.name}"
                                           data-item-key="{$Video.slug}"
                                           data-item-index="{$Iteration}"
                                           data-gallery-type="videos"
                                           class="film-strip-thumb-wrapper"
                                           style="background-image:url({$MediaData.data.folder}{$Video.thumbnail})"
                                           href="/media/videos/{$Video.slug}"
                                           itemProf="isPartOf">
                                            <span class="film-strip-thumb-frame"></span>
                                        </a>
                                        {$Iterations = $Iterations + 1}
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        <div class="ajax-frame">
                            <table>
                                <tr>
                                    <td id="film-strip-ajax-target">
                                        <meta itemprop="contentUrl" content="//{$smarty.server.HTTP_HOST}/Uploads/Media/Videos/{$MediaData.file_name}" />
                                        <meta itemprop="thumbnail" content="//{$smarty.server.HTTP_HOST}/Uploads/Media/Videos/{$MediaData.thumbnail}" />
                                        <div id="flash-container">
                                            <object
                                                    type="application/x-shockwave-flash"
                                                    data="//{$smarty.server.HTTP_HOST}/Templates/{$Template}/parts/video-player.swf"
                                                    width="704"
                                                    height="299"
                                                    id="flash-video"
                                                    style="visibility: visible;">
                                                <param name="allowFullScreen" value="true" />
                                                <param name="bgcolor" value="#000000" />
                                                <param name="allowScriptAccess" value="always" />
                                                <param name="wmode" value="opaque" />
                                                <param name="menu" value="false" />
                                                <param name="flashvars" value="flvPath=//{$smarty.server.HTTP_HOST}/Uploads/Media/Videos/{$MediaData.file_name}&amp;flvWidth=704&amp;flvHeight=299&amp;captionsDefaultOn=true&amp;ratingFadeTime=1&amp;ratingShowTime=4&amp;autoPlay=true&amp;ratingPath=//{$smarty.server.HTTP_HOST}/Templates/{$Template}/images/services/blizzard-logo.png&amp;locale=ru-ru&amp;dateFormat=dd/MM/yyyy" />
                                            </object>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!-- TODO: Need to create Paging System -->
                        </div>
                    </div>
                    <div id="media-meta-data"><div>
                            <div class="meta-data">
                                <div id="item-title" itemprop="name">{$MediaData.name}</div>
                                <dl class="meta-details">
                                    <dt>{#Media_PublishedOn#} </dt>
                                    <dd>{$MediaData.publish_date|date_format:"d/m/Y"}</dd>
                                    <dt class="dt-separator">{#Media_Author#}:</dt>
                                    <dd>{$MediaData.author}</dd>
                                </dl>
                                <span class="clear"><!-- --></span>
                            </div>
                        </div></div>
                </div>
            </div>
            <div style="display:none" id="media-preload-container"></div>
        </div>
    </div>
</div>
{include file="footer.tpl"}