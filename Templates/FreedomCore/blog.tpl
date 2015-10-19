{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol  class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">World of Warcraft</span>
                    </a>
                </li>
                <li class="last" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/blog/{$Article.id}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Article.title}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="right-sidebar" >
                {include file = 'sidebar/client.tpl'}
                <div class="sidebar" id="sidebar">
                    <div class="sidebar-top">
                        <div class="sidebar-bot">
                            <div id="dynamic-sidebar-target">
                                <div class="sidebar-module " id="sidebar-recent-articles">
                                    <div class="sidebar-title">
                                        <h3 class="header-3 title-recent-articles">
                                            {#Articles_Latest#}
                                        </h3>
                                    </div>
                                    <div class="sidebar-content">
                                        <ul id="recent-articles" class="articles-list-plain">
                                            {foreach $Articles as $Item}
                                                <li>
                                                    <a class="article-block on-view" href="/blog/{$Item.id}">
                                                        <span class="image" style="background-image: url('/Uploads/{$Item.post_miniature}');"></span>
                                                        <span class="title">{$Item.title}</span>
                                                        <span class="date">{$Item.post_date|relative_date}</span>
                                                        <span class="clear"><!-- --></span>
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
            </div>
            <div class="left-content">
                <div id="blog" class="article-wrapper" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
                    <h2 class="header-2">
                        <span itemprop="headline">{$Article.title}</span>
                    </h2>
                    <div class="article-meta">
                        <a class="article-author" href="/search?a={$Article.posted_by}&amp;f=article">
                            <span class="author-icon"></span>
                            <span itemprop="author">{$Article.posted_by}</span>
                        </a>
                        <span class="publish-date" title="{$Article.post_date}">
                        {$Article.post_date}
                        </span>
                        <a href="#comments" class="comments-link">{$Article.comments_count}</a>
                        <div class="article-content">
                            <div class="header-image">
                                <img itemprop="image" alt="{$Article.title}" src="/Uploads/{$Article.post_miniature}" />
                            </div>
                            <div class="detail" itemprop="articleBody">
                                <p>
                                    {$Article.full_description}
                                </p>
                            </div>
                        </div>
                        <div class="keyword-list"></div>
                    </div>
                </div>
                <div id="comments" class="bnet-comments ">
                    <h2 class="subheader-2" >{#Comments_Loading#}…</h2>
                    <h2 class="hide" >{#Comments_Error_Loading#}.</h2>
                    <div class="comments-loading"></div>
                    <script type="text/javascript">
                        //<![CDATA[
                        $(function() {
                            Comments.initialize('{$Language}.blog.{$Article.id}', '{$Article.comments_key}', '0');
                        });
                        //]]>
                    </script>
                </div>
            </div>
            <span class="clear"><!-- --></span>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    Blog.init();
                });
                //]]>
            </script>
        </div>
    </div>
 </div>
{include file="footer.tpl"}