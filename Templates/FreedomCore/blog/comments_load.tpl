{if count($Comments) == 0}
    <div id="comments" class="bnet-comments comments-error">
        <h2 class="subheader-2">{#Comments_Not_Found#}.</h2>
        {include file='blog/comments_add_form.tpl'}
    </div>
{elseif count($Comments) > 0}

    <div id="comments" class="bnet-comments ">
        <h2 class="subheader-2">{#Comments#} (<span id="comments-total">{$Comments|count}</span>)</h2>
        <a class="comments-pull-link" href="javascript:;" id="comments-pull" onclick="Comments.loadBase();" style="display: none">
            <span class="pull-single" style="display: block">{#Comments_new#}: <span>{0}</span>.<strong>{#Renew#}?</strong></span>
            <span class="pull-multiple" style="display: none">{#Comments_new#}: <span>{0}</span>.<strong>{#Renew#}?</strong></span>
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
            <div id="comments-pages">
                <div id="comments-list-wrapper">
                    <ul class="comments-list" id="comments-1">
                        {foreach $Comments as $Comment}
                            <li class id="post-{$Comment.id}">
                                <div id='comment-tile' class="comment-tile">
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
                                    <div class="rate-post-wrapper" >
                                        <a href="javascript:;" class="rate-option rate-up" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="up" data-report-type="1">
                                            <span class="button-left">
                                                <span class="button-right">{#Comments_Like#}</span>
                                            </span>
                                        </a>
                                        <div class="rate-option downvote-wrapper ">
                                            <a href="javascript:;" onclick="$(this).next('.downvote-menu').toggle();" class="rate-down"/>
                                            <div class="downvote-menu" style="display:none">
                                                <div class="ui-dropdown">
                                                    <div class="dropdown-wrapper">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="1">{#Comments_DisLike#}</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="2">{#Comments_Trolling#}</a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="down" data-report-type="3">{#Comments_Spam#}</a>
                                                            </li>
                                                            <li class="report-comment">
                                                                <a href="javascript:;" data-post-id="{$Comment.id}" data-post-author="{$Comment.comment_by}" data-vote-type="report">{#Comments_Report#}</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="clear"><!-- --></span>
                                    </div>
                                    <div class="bnet-avatar">
                                        <div class="avatar-outer">
                                            <a href="/account/profile/{$Comment.comment_by}">
                                                <img height="64" width="64" src="" alt="" />
                                                <span class="avatar-inner"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="comment-head">
                                        <div class="bnet-username" itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person">
                                            <div id="context-5" class="ui-context" style="display: none">
                                                <div class="context">
                                                    <a href="javascript:;" class="close" onclick="return CharSelect.close(this);"></a>
                                                    <div class="context-user">
                                                        <strong>{$Comment.comment_by}</strong>
                                                    </div>
                                                    <div class="context-links">
                                                        <a href="" title="{#Profile#}" rel="np" class="icon-profile link-first">
                                                           {#Profile#}
                                                        </a>
                                                        <a href="" title="{#Posts_My#}" rel="np" class="icon-posts link-last"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="/account/profile/{$Comment.comment_by}" itemprop="url" class="context-link">
                                                <span itemprop="name" class="poster-name">{$Comment.comment_by}</span>
                                            </a>
                                            <span class="timestamp">{$Comment.comment_date|relative_date}</span>
                                        </div>
                                    </div>
                                    <div class="comment-body">
                                        {$Comment.comment_text}
                                    </div>
                                    <div class="comment-foot">
                                        <button class="ui-button button2 reply-button" type="button" onclick="Comments.reply('{$Comment.id}', '1', '{$Comment.comment_by}'); return false;">
                                            <span class="button-left">
                                                <span class="button-right">
                                                    Ответить
                                                </span>
                                            </span>
                                        </button>
                                        <span class="clear"><!-- --></span>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
        <div id="report-post" class="report-post type-forums">
            <div id="report-table">

                <div class="report-desc">
                </div>
                <div class="report-detail report-data">
                    <h3 class="subheader-3">
                        Сообщить модераторам о сообщении #<span id="offensive-post-id"></span> игрока <span id="offensive-post-author"></span>
                    </h3>
                </div>

                <div class="report-desc">
                    Причина
                </div>
                <div class="report-detail">
                    <select id="report-reason" required="required">
                        <option value="SPAMMING">Спам</option>
                        <option value="ILLEGAL">Противозаконно</option>
                        <option value="NOT_SPECIFIED">Не указано</option>
                        <option value="OTHER">Иное</option>
                        <option value="REAL_LIFE_THREATS">Угрозы в реальной жизни</option>
                        <option value="ADVERTISING_STRADING">Реклама</option>
                        <option value="HARASSMENT">Оскорбления</option>
                        <option value="BAD_LINK">«Битая» ссылка</option>
                        <option value="TROLLING">Троллинг</option>
                    </select>
                </div>

                <div class="report-desc">
                    Объяснение <small>(не более 256 символов)</small>
                </div>
                <div class="report-detail">
                    <textarea id="report-detail" class="post-editor" cols="78" rows="13" required="required" maxlength="256"></textarea>
                </div>
                <div class="report-submit-wrapper">

                    <a class="ui-button button1 report-submit" href="javascript:;"><span class="button-left"><span class="button-right">Отправить</span></span></a>
                    <a class="cancel-report" href="javascript:;">Отмена</a>
                </div>
            </div>
            <div id="report-success" class="report-success">


                <h3 class="header-3">Готово!</h3>

                [<a href="javascript:;" onclick="$(&quot;#report-post&quot;).hide()">Закрыть</a>]
            </div>
        </div>
    </div>
{else}
    <div id="comments" class="bnet-comments comments-error">
        <h2 class="subheader-2">{#Comments_Not_Found#}.</h2>
        <div class="comments-loading"/>
    </div>
{/if}