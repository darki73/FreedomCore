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
                        <script type="text/javascript">
                            var TopicID = '{$TopicData.topic.id}';
                            var ForumID = '{$TopicData.category.id}';
                        </script>
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
                        <div id="post-{$Reply.id}" class="topic-post post-{$i+1} ajax-update" data-post-id="{$Reply.id}">
                            <span id="{$Reply.post_id}"></span>
                            <div class="post-interior" itemscope="itemscope" itemtype="http://schema.org/Comment">
                                <div class="post-character">
                                    <div class="forum-user">
                                        <div class="forum-avatar">
                                            <div class="bnet-avatar">
                                                <div class="avatar-outer">
                                                    <a href="/character/{$Reply.posted_by}">
                                                        <img height="64" width="64" src="/Templates/{$Template}/images/2d/avatar/{$Reply.race}-{$Reply.gender}.jpg" alt>
                                                        <span class="avatar-inner"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="user-details">
                                            <div class="bnet-username" itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person">
                                                <div id="context-2" class="ui-context" style="display: none;">
                                                    <div class="context">
                                                        <a href="javascript:;" class="close" onclick="return CharSelect.close(this);"></a>
                                                        <div class="context-user">
                                                            <strong>{$Reply.posted_by}</strong>
                                                        </div>
                                                        <div class="context-links">
                                                            <a href="/character/{$Reply.posted_by}/" title="{#Profile#}" rel="np" class="icon-profile link-first">
                                                                <span class="context-icon"></span>{#Profile#}
                                                            </a>
                                                            <a href="/search?f=post&amp;a={$Reply.posted_by}&amp;sort=time" title="{#Posts_My#}" rel="np" class="icon-posts link-last">
                                                                <span class="context-icon"></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="/character/{$Reply.posted_by}/" itemprop="url" class="context-link wow-class-{$Reply.class}">
                                                    <span itemprop="name" class="poster-name">{$Reply.posted_by}</span>
                                                </a>
                                            </div>
                                            <div class="user-character">
                                                <div class="character-desc">
                                                    <span class="wow-class-{$Reply.class}">
                                                        {$Reply.class_name}-{$Reply.race_name} {$Reply.level} {#LevelShort#}
                                                    </span>
                                                </div>
                                                <div class="achievements">{$Reply.apoints}</div>
                                            </div>
                                        </div>
                                        <span class="clear"></span>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <div class="post-detail" id="post-text-id{$Reply.post_id}" itemprop="text">
                                        {$Reply.post_message}
                                    </div>
                                </div>
                                <div class="post-info">
                                    <div class="post-info-wrapper">
                                        <a class="post-index" href="#{$Reply.post_id}">#{$Reply.post_id}</a>
                                        <div class="date" data-tooltip="{$Reply.post_time|date_format:"%d/%m/%Y %H:%M"}">{$Reply.post_time|date_format:"%d/%m/%Y"}</div>
                                        <meta itemprop="dateCreated" content="{$Reply.post_time|date_format:"%Y-%m-%dT%H:%M:%S"}" />
                                        <meta itemprop="discussionUrl" content="/forum/topic/{$TopicData.category.id}" />
                                        <div class="rate-post-wrapper">
                                            <a href="javascript:;" class="rate-option rate-up" data-post-id="{$Reply.post_id}" data-post-author="{$Reply.posted_by}" data-vote-type="up" data-report-type="1">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        {#Comments_Like#}
                                                    </span>
                                                </span>
                                            </a>
                                            <div class="rate-option downvote-wrapper">
                                                <a href="javascript:;" onclick="$(this).next('.downvote-menu').toggle();" class="rate-down"/>
                                                <div class="downvote-menu" style="display:none">
                                                    <div class="ui-dropdown">
                                                        <div class="dropdown-wrapper">
                                                            <ul>
                                                                <li>
                                                                    <a href="javascript:;" data-post-id="{$Reply.post_id}" data-post-author="{$Reply.posted_by}" data-vote-type="down" data-report-type="1">Dislike</a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" data-post-id="{$Reply.post_id}" data-post-author="{$Reply.posted_by}" data-vote-type="down" data-report-type="2">Trolling</a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" data-post-id="{$Reply.post_id}" data-post-author="{$Reply.posted_by}" data-vote-type="down" data-report-type="3">Spam</a>
                                                                </li>
                                                                <li class="report-comment">
                                                                    <a href="javascript:;" data-post-id="{$Reply.post_id}" data-post-author="{$Reply.posted_by}" data-vote-type="report">Report</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="clear"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-options" data-solution-text="" data-mark-solution-text="" data-marked-as-solution-text="">
                                    <a class="ui-button button2 reply-to-post" href="#new-post">
                                        <span class="button-left">
                                            <span class="button-right">{#MSG_BML_reply#}</span>
                                        </span>
                                    </a>
                                    <a class="ui-button button2 quote-post" href="#new-post" onclick="ForumTopic.quoteTopic(this);" data-post-id="{$Reply.post_id}">
                                        <span class="button-left">
                                            <span class="button-right">
                                                <snap clas="icon-quote">
                                                    {#MSG_BML_quote#}
                                                </snap>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="forum-actions forum-actions-bottom">
                    <div class="actions-right"></div>
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
                {include file = 'forum/comment.tpl'}
                <span class="clear"></span>
                <div class="report-violation">
                    <div class="report-violation-inner">
                        <div class="report-violation-icon"></div>
                        <div class="report-violation-details">
                            <h3 class="subheader-3">
                                {#Violation_Please_Report#}
                            </h3>
                            <p>
                                {#Violation_Violence#}
                            </p>
                            <p>
                                {#Violation_PersonalInformation#}
                            </p>
                            <p>
                                {#Violation_Discrimination#}
                            </p>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[

                $(function(){
                    ReportPost.initialize("#post-list", "forums");
                    ForumTopic.initialize("#post-list", {$TopicData.category.id}, {$TopicData.topic.id}, 0,
                            {$TopicData.replies|count});
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file = 'footer.tpl'}