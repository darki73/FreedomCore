{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div  class="content-trail">
            <ol class="ui-breadcrumb">
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="slideshow" class="ui-slideshow">
                <div class="slideshow">

                    {for $i = 0; $i < count($Slideshow); $i++}
                        <div class="slide" id="slide-{$i}" style="background-image: url('/Uploads/Core/Slideshow/{$Slideshow.$i.image}'); {if $i != 0} display: none;{/if}"></div>
                    {/for}
                </div>

                <div class="paging">
                    <a href="javascript:;" class="prev" onclick="Slideshow.prev();"></a>
                    <a href="javascript:;" class="next" onclick="Slideshow.next();"></a>
                </div>

                <div class="caption">
                    <h3><a href="javascript:;" class="link"></a></h3>

                </div>

                <div class="preview"></div>
                <div class="mask"></div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    Slideshow.initialize('#slideshow', [
                        {for $i = 0; $i < count($Slideshow); $i++}
                        {ldelim}
                            image: "/Uploads/Core/Slideshow/{$Slideshow.$i.image}",
                            desc: "{$Slideshow.$i.description}",
                            title: "{$Slideshow.$i.title}",
                            url: "{$Slideshow.$i.url}",
                            id: "{$Slideshow.$i.id}",
                            duration: {$Slideshow.$i.duration}
                            {rdelim},
                        {/for}
                    ]);

                });
                //]]>
            </script>

            <div class="right-sidebar" >
                <div class="sidebar" id="sidebar">
                    <div class="sidebar-top">
                        <div class="sidebar-bot">
                            <div class="sidebar-loading" id="sidebar-loading">
                                {#Content_loading#}…
                            </div>
                            <div id="dynamic-sidebar-target"></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    //<![CDATA[
                    $(function() {
                        Sidebar.sidebar([
                            { "type": "client", "query": "" },
                            { "type": "realm-status", "query": "" },
                            { "type": "events", "query": "" },
                            { "type": "under-dev", "query": "" },
                            {if $Debug}
                            { "type": "debugger", "query": "" },
                            {/if}
                        ]);
                    });
                    //]]>
                </script>
            </div>
            <div class="left-content" itemscope="itemscope" itemtype="http://schema.org/WebPageElement">
                <div class="left-container-inner">
                    <div class="featured-news-container">
                        <ul class="featured-news">
                            {assign "Iteration" "0"}
                            {foreach $News as $Article}
                                <li>
                                    <div class="article-wrapper">
                                        <a href="/blog/{$Article.id}/{$Article.slugged_url}" class="featured-news-link" data-category="wow" data-action="Blog_Click-Throughs" data-label="home ">
                                            <div class="article-image" style="background-image:url(/Uploads/{$Article.post_miniature})">
                                                <div class="article-image-frame"></div>
                                            </div>
                                            <div class="article-content">
                                                <span class="article-title" title="{$Article.title}">{$Article.title}</span>
                                                <span class="article-summary">{$Article.short_description}</span>
                                            </div>
                                        </a>
                                        <div class="article-meta">
                                            <a href="/blog/{$Article.id}#comments" class="comments-link">{$Article.comments_count}</a>
                                            <span class="publish-date" title="{$Article.post_date}">{$Article.post_date|relative_date}</span>
                                        </div>
                                    </div>
                                </li>
                                {$Iteration = $Iteration + 1}
                                {if $Iteration == 3}
                                    {break}
                                {/if}
                            {/foreach}
                        </ul>
                    </div>
                    <div id="blog-articles" class="blog-articles" itemscope="itemscope" itemtype="http://schema.org/Blog">
                        {foreach $News as $Article}
                            <div class="article-wrapper" >
                                <a href="/blog/{$Article.id}/{$Article.slugged_url}">
                                    <div class="article-image" style="background-image:url(/Uploads/{$Article.post_miniature})">
                                        <div class="article-image-frame"></div>
                                    </div>

                                    <div class="article-content">
                                        <h2 class="header-2"><span class="article-title">{$Article.title}</span></h2><span class="clear"><!-- --></span>

                                        <div class="article-summary">
                                            {$Article.short_description}
                                        </div><span class="clear"><!-- --></span>
                                        <meta content="{$Article.post_date}">
                                        <meta content="{$Language}">
                                        <meta content="UserComments:{$Article.comments_count}">
                                        <meta content=
                                              "/Uploads/{$Article.post_miniature}">
                                    </div></a>

                                <div class="article-meta">
                                    <span class="publish-date" title="{$Article.post_date}">{$Article.post_date|relative_date}</span>
                                    <a class="comments-link" href="/blog/{$Article.id}#comments">{$Article.comments_count}</a>
                                </div><span class="clear"><!-- --></span>
                            </div>

                        {/foreach}
                    </div>
                    <span class="clear"></span>
                </div>
            </div>
        </div>
    </div>
 </div>

{include file="footer.tpl"}