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
                    <a href="/game/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Game#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/profession" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Professions#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/profession/{$Profession.profession_name}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Profession.profession_translation}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki wiki-profession">
                <div class="sidebar">
                    <table class="media-frame">
                        <tr>
                            <td class="tl"></td>
                            <td class="tm"></td>
                            <td class="tr"></td>
                        </tr>
                        <tr>
                            <td class="ml"></td>
                            <td class="mm">
                                <a href="javascript:;" class="thumbnail" onclick="Lightbox.loadImage([{ src: '/Templates/{$Template}/images/wiki/profession/screenshots/{$Profession.profession_name}.jpg' }]);">
                                    <span class="view"></span>
                                    <img src="/Templates/{$Template}/images/wiki/profession/thumbnails/{$Profession.profession_name}.jpg" width="265" alt="" />
                                </a>
                            </td>
                            <td class="mr"></td>
                        </tr>
                        <tr>
                            <td class="bl"></td>
                            <td class="bm"></td>
                            <td class="br"></td>
                        </tr>
                    </table>
                </div>
                <div class="info">
                    <div class="title">
                        <h2>
		                    <span class="icon-frame frame-56 circle-frame" style="background-image: url(&quot;/Templates/{$Template}/images/icons/large/trade_{$Profession.profession_name}.jpg&quot;);"></span>
                            {$Profession.profession_translation}
                            <span class="clear"><!-- --></span>
                        </h2>
                    </div>
                    <p class="intro">
                        {$Profession.profession_long_description}
                    </p>
                </div>
                <span class="clear"></span>
                <script type="text/javascript">
                    //<![CDATA[
                    $(function() {
                        Wiki.pageUrl = '/game/profession/{$Profession.profession_name}/';
                    });
                    //]]>
                </script>
            </div>
            <span class="clear"></span>
            <div class="comment-section">
                <div id="comments" class="bnet-comments ">
                    <h2 class="subheader-2" >{#Comments_Loading#}…</h2>
                    <h2 class="hide" >{#Comments_Error_Loading#}.</h2>
                    <div class="comments-loading"></div>
                    <script type="text/javascript">
                        //<![CDATA[
                        $(function() {
                            Comments.initialize('{$Language}.profession.{$Profession.id}', '{$Profession.comments_key}', '0');
                        });
                        //]]>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}