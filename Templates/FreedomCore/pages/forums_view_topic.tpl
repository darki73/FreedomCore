{include file = 'header.tpl'}
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
                    <a href="/forum/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Forum_Page_Title#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/forum/#forum{$TopicData.type.id}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$TopicData.type.name}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/forum/{$TopicData.category.id}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$TopicData.category.name}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/forum/{$TopicData.topic.id}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$TopicData.topic.name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="forum-wrapper" data-forum-id="{$TopicData.category.id}">
                <div class="topic-header-wrapper">
                    <div class="topic-header">
                        <h2 class="subheader-2">
                            <span class="topic-title">{$TopicData.topic.name}</span>
                        </h2>
                        <div class="topic-status-buttons">
                            <button class="ui-button request-sticky button2" type="button" onclick="ForumTopic.requestSticky(this, '{$TopicData.topic.id}')" data-tooltip="{#Forum_Make_It_Sticky_Full#}">
                                <span class="button-left">
                                    <span class="button-right">
                                        {#Forum_Make_It_Sticky#}
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <span class="clear"></span>
                <div class="forum-actions forum-actions-top">
                    <div class="actions-right"></div>
                    <div class="reply-button-wrapper ajax-update">
                        <a class="ui-button button1" href="#new-post">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Forum_Reply#}
                                </span>
                            </span>
                        </a>
                    </div>
                    <span class="clear"><!-- --></span>
                </div>
                <div id="post-list" class="post-list">
                    {foreach from=$TopicData.replies item=Reply key=i}
                        <div id="post-{$Reply.id}" class="topic post-{$i+1} ajax-update" data-post-id="{$Reply.id}">
                            <span id="{$Reply.post_id}"></span>
                            <div class="post-interior" itemscope="itemscope" itemtype="http://schema.org/Comment">
                                <div class="post-character">

                                </div>
                                <div class="post-content">

                                </div>
                                <div class="post-info">

                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>

            <script type="text/javascript">
                //<![CDATA[

                $(function(){
                    ReportPost.initialize("#post-list", "forums");
                    ForumTopic.initialize("#post-list", {$TopicData.category.id}, {$TopicData.topic.id}, 0,
                            1);

                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file = 'footer.tpl'}