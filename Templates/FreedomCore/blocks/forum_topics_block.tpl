<table class="forum-topics" id="forum-topics" data-forum-id="{$Forum.forum_id}" style="font-size: 13px;">
    <thead>
    <tr>
        <th class="icon-column"></th>
        <th class="subject-column">{#Forum_Table_Topic_Name#}</th>
        <th class="author-column">{#Forum_Table_Author_Name#}</th>
        <th class="reply-column">{#Forum_Table_Responses_Name#}</th>
        <th class="view-column">{#Forum_Table_Views_Name#}</th>
        <th class="last-poster-column">{#Forum_Table_LastMessage_Name#}</th>
    </tr>
    </thead>
    <tbody class="regular-topics sort-connect">
    {foreach $Forum.topics as $Topic}
        <tr id="postRow{$Topic.id}" class="regular-topic" data-topic-id="{$Topic.id}" itemscope="itemscope" itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
            <td class="icon-cell">
                                    <span class="topic-icon-wrapper">
                                        <span class="topic-icon"></span>
                                    </span>
            </td>
            <td class="title-cell" data-tooltip="#topic-{$Topic.id}" data-tooltip-options="{ &quot;location&quot;: &quot;mouse&quot; }">
                <a class="topic-title" itemprop="url" href="/forum/topic/{$Topic.id}">
                    <span itemprop="headline">{$Topic.topic}</span>
                </a>
                                    <span id="topic-{$Topic.id}" style="display:none">
                                        <span class="topic-tooltip">
                                            <span class="topic-detail">{$Topic.message|truncate:100}</span>
                                            <span class="created">{$Topic.post_time|relative_date}</span>
                                            <span class="info">
                                                <span class="views">{$Topic.views} {#Forum_Topic_View#} / {$Topic.replies} {#Forum_Topic_View#}</span>
                                                <span class="last-post">{#Forum_Topic_LastReply#} {$Topic.last_reply_data.postername} ({$Topic.last_reply_data.post_time|relative_date})</span>
                                            </span>
                                        </span>
                                    </span>
                <meta itemprop="dateCreated" content="{$Topic.post_time|date_format:"%Y-%m-%dT%H:%M:%S"}" />
                <meta itemprop="dateModified" content="{$Topic.post_time|date_format:"%Y-%m-%dT%H:%M:%S"}" />
                <a class="last-read-post hidden" href="/forum/topic/{$Topic.id}" data-topic-id="{$Topic.id}" data-topic-last-posted="{$Topic.last_reply_data.post_time}" data-topic-replies="{$Topic.replies}">
                    <span class="last-read-arrow"></span>
                </a>
                <div class="pages-wrapper post-pages-cell">
                </div>
            </td>
            <td  class="author-cell">
                                    <span class="author-wrapper">
                                        <span class="author-name" itemprop="author">{$Topic.postername}</span>
                                    </span>
            </td>
            <td class="reply-cell">
                {$Topic.replies}<meta itemprop="interactionCount" content="UserComments:{$Topic.replies}" />
            </td>
            <td class="view-cell">
                {$Topic.views}<meta itemprop="interactionCount" content="UserPageVisits:{$Topic.views}" />
            </td>
            <td class="author-cell last-post-cell" data-tooltip="#last-post-{$Topic.id}">
                <a class="last-post-link" href="/forum/topic/{$Topic.id}">
                                        <span class="author-wrapper">
                                            <span class="author-name">
                                                {$Topic.last_reply_data.postername}
                                            </span>
                                        </span>
                                        <span class="last-post-time">
                                        {$Topic.last_reply_data.post_time|relative_date_short}
                                        </span>
                </a>
                                    <span id="last-post-{$Topic.id}" style="display:none">
                                        <span class="last-post">{#Forum_Topic_LastReply#} {$Topic.last_reply_data.postername} ({$Topic.last_reply_data.post_time|relative_date})</span>
                                    </span>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>