{if count($Comments) == 0}
    <div id="comments" class="bnet-comments comments-error">
        <h2 class="subheader-2">{#Comments_Not_Found#}.</h2>
        {include file='blog/comments_add_form.tpl'}
    </div>
{elseif count($Comments) > 0}
    <div id="comments" class="bnet-comments ">
        <h2 class="subheader-2">{#Comments#} (<span id="comments-total">{$Comments|count}</span>)</h2>
        <a class="comments-pull-link" href="javascript:;" id="comments-pull" onclick="Comments.loadBase();" style="display: none">
            <span class="pull-single" style="display: block">{#Comments_new#}: <span>{ldelim}0{rdelim}</span>.<strong>{#Renew#}?</strong></span>
            <span class="pull-multiple" style="display: none">{#Comments_new#}: <span>{ldelim}0{rdelim}</span>.<strong>{#Renew#}?</strong></span>
        </a>
        {include file='blog/comments_add_form.tpl'}
        <div id="comments-sorting-wrapper">
            <ul class="tab-menu " id="comments-sorting-tabs">
                <li class="menu-best ">
                    <a href="#best" class="tab-active">
                        <span>{#Comments_Popular#}</span>
                    </a>
                </li>
                <li class="menu-latest ">
                    <a href="#latest">
                        <span>{#Comments_Latest#}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="comments-pages-wrapper">
            <div class="comments-pages">
                <div id="comments-list-wrapper">
                    <div class="comments-controls">
                        <!-- Pagination is not yet done -->
                    </div>
                    <ul class="comments-list" id="comments-1">
                        {foreach $Comments as $Comment}
                            {if $Comment.replied_to == null}
                                <li class="" id="post-{$Comment.id}">
                                    <div class="comment-tile">
                                        <div class="rate-post-wrapper rate-post-login comment-rating">
                                            {$VotesResult = $Comment.votes_up - $Comment.votes_down}
                                            {if $VotesResult != 0}
                                                {if $VotesResult > 0}
                                                    <font color="green">+{$VotesResult}</font>
                                                {else}
                                                    <font color="red">{$VotesResult}</font>
                                                {/if}
                                            {/if}
                                        </div>
                                        <div class="rate-post-wrapper">
                                            <a href="javascript:;" class="rate-option rate-up" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="up" data-report-type="1">
                                            <span class="button-left">
                                                <span class="button-right">
                                                    Like
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
                                                                    <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="1">
                                                                        Dislike
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="2">
                                                                        Trolling
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="3">
                                                                        Spam
                                                                    </a>
                                                                </li>
                                                                <li class="report-comment">
                                                                    <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="report">
                                                                        Report
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="clear"></span>
                                        </div>
                                        <div class="bnet-avatar">
                                            <div class="avatar-outer">
                                                <a href="/character/{$Comment.comment_by}">
                                                    <img height="64" width="64" src="/Templates/{$Template}/images/2d/avatar/{$Comment.poster_race}-{$Comment.poster_gender}.jpg" alt=""/>
                                                    <span class="avatar-inner"/>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="comment-head">
                                            <div class="bnet-username" itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person">
                                                <div id="context-3" class="ui-context">
                                                    <div class="context">
                                                        <a href="javascript:;" clas="close" onclick="return CharSelect.close(this);"/>
                                                        <div class="context-user">
                                                            <strong>{$Comment.comment_by}</strong>
                                                        </div>
                                                        <div class="context-links">
                                                            <a href="/character/{$Comment.comment_by}" title="Profile" rel="np" class="icon-profile link-first">
                                                                <span class="context-icon"/>
                                                                Profile
                                                            </a>
                                                            <a href="/search?f=post&a={$Comment.comment_by}&sort=time" title="View posts" rel="np" class="icon-posts link-last">
                                                                <span class="context-icon"/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="/character/{$Comment.comment_by}"  itemprop="url" class="context-link wow-class-{$Comment.poster_class}">
                                                    <span itemprop="name" class="poster-name">{$Comment.comment_by}</span>
                                                </a>
                                                <span class="timestamp">{$Comment.comment_date|relative_date}</span>
                                            </div>
                                        </div>
                                        <div class="comment-body">
                                            {$Comment.comment_text}
                                        </div>
                                        {if $Characters != 0}
                                            <div class="comment-foot">
                                                {if $Comment.comment_by == $CommentCharacter.name}
                                                    <button class="ui-button button2" type="button" onclick="Comments.toggleDelete('{$Comment.id}');" data-tooltip="Posts may be deleted within 15 minutes of posting.">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        Delete
                                                    </span>
                                                </span>
                                                    </button>
                                                {/if}
                                                <button class="ui-button button2 reply-button" type="button" onclick="Comments.reply('{$Comment.id}', {$Comment.id}, '{$Comment.comment_by}'); return false;">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        Reply
                                                    </span>
                                                </span>
                                                </button>
                                                <span class="clear"><!-- --></span>
                                            </div>
                                        {/if}
                                        <span class="clear"></span>
                                    </div>
                                </li>
                                {if !empty($Comment.nested_comments)}
                                    {foreach $Comment.nested_comments as $CNC}
                                        <li class=" comment-nested" id="post-{$CNC.id}">
                                            <div class="comment-tile">
                                                <div class="rate-post-wrapper rate-post-login comment-rating">
                                                    {$VotesResult = $CNC.votes_up - $CNC.votes_down}
                                                    {if $VotesResult != 0}
                                                        {if $VotesResult > 0}
                                                            <font color="green">+{$VotesResult}</font>
                                                        {else}
                                                            <font color="red">{$VotesResult}</font>
                                                        {/if}
                                                    {/if}
                                                </div>
                                                <div class="rate-post-wrapper">
                                                    <a href="javascript:;" class="rate-option rate-up" data-post-id="{$CNC.id}" data-post-author="{$CNC.comment_by}" data-vote-type="up" data-report-type="1">
                                            <span class="button-left">
                                                <span class="button-right">
                                                    Like
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
                                                                            <a href="javascript:;" data-post-id="{$CNC.id}" data-post-author="{$CNC.comment_by}" data-vote-type="down" data-report-type="1">
                                                                                Dislike
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:;" data-post-id="{$CNC.id}" data-post-author="{$CNC.comment_by}" data-vote-type="down" data-report-type="2">
                                                                                Trolling
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:;" data-post-id="{$CNC.id}" data-post-author="{$CNC.comment_by}" data-vote-type="down" data-report-type="3">
                                                                                Spam
                                                                            </a>
                                                                        </li>
                                                                        <li class="report-comment">
                                                                            <a href="javascript:;" data-post-id="{$CNC.id}" data-post-author="{$CNC.comment_by}" data-vote-type="report">
                                                                                Report
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="clear"></span>
                                                </div>
                                                <div class="bnet-avatar">
                                                    <div class="avatar-outer">
                                                        <a href="/character/{$CNC.comment_by}">
                                                            <img height="64" width="64" src="/Templates/{$Template}/images/2d/avatar/{$CNC.poster_race}-{$CNC.poster_gender}.jpg" alt=""/>
                                                            <span class="avatar-inner"/>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="comment-head">
                                                    <div class="bnet-username" itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person">
                                                        <div id="context-3" class="ui-context">
                                                            <div class="context">
                                                                <a href="javascript:;" clas="close" onclick="return CharSelect.close(this);"/>
                                                                <div class="context-user">
                                                                    <strong>{$CNC.comment_by}</strong>
                                                                </div>
                                                                <div class="context-links">
                                                                    <a href="/character/{$CNC.comment_by}" title="Profile" rel="np" class="icon-profile link-first">
                                                                        <span class="context-icon"/>
                                                                        Profile
                                                                    </a>
                                                                    <a href="/search?f=post&a={$CNC.comment_by}&sort=time" title="View posts" rel="np" class="icon-posts link-last">
                                                                        <span class="context-icon"/>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="/character/{$CNC.comment_by}"  itemprop="url" class="context-link wow-class-{$CNC.poster_class}">
                                                            <span itemprop="name" class="poster-name">{$CNC.comment_by}</span>
                                                        </a>
                                                        <span class="timestamp">{$CNC.comment_date|relative_date}</span>
                                                    </div>
                                                </div>
                                                <div class="comment-body">
                                                    {$CNC.comment_text}
                                                </div>
                                                {if $Characters != 0}
                                                    <div class="comment-foot">
                                                        {if $CNC.comment_by == $CommentCharacter.name}
                                                            <button class="ui-button button2" type="button" onclick="Comments.toggleDelete('{$CNC.id}');" data-tooltip="Posts may be deleted within 15 minutes of posting.">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        Delete
                                                    </span>
                                                </span>
                                                            </button>
                                                        {/if}
                                                        <button class="ui-button button2 reply-button" type="button" onclick="Comments.reply('{$CNC.id}', {$CNC.id}, '{$CNC.comment_by}'); return false;">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        Reply
                                                    </span>
                                                </span>
                                                        </button>
                                                        <span class="clear"><!-- --></span>
                                                    </div>
                                                {/if}
                                                <span class="clear"></span>
                                            </div>
                                        </li>
                                    {/foreach}
                                {/if}
                            {/if}
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            //<![CDATA[
            Comments.count = {$Comments|count};
            //]]>
        </script>
    </div>
{else}
    <div id="comments" class="bnet-comments comments-error">
        <h2 class="subheader-2">{#Comments_Not_Found#}.</h2>
        <div class="comments-loading"/>
    </div>
{/if}