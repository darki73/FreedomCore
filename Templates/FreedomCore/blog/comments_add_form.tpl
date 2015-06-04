{if !$smarty.session.loggedin}
        <div class="comments-form-wrapper">
            <div class="comments-error-gate">
                <p>{#Login_Authorization_Needed#}</p>
                <button class="ui-button button1" onclick="Login.open();" type="button"><span class="button-left"><span class="button-right">{#Login_Log_In#}</span></span></button>
            </div>
        </div>
    {else}
        <div class="comments-form-wrapper">
            <form action="" class="comments-form" id="comments-add-form" method="post"
                  name="comments-add-form">
                <div class="bnet-avatar">
                    <div class="avatar-outer">
                        <a href="/account/profile/{$User.username}/">
                            <img alt="" height="64" src= "" width="64">
                            <span class="avatar-inner"></span>
                        </a>
                    </div>
                </div>

                <div class="character-info user ajax-update">
                    <div class="bnet-username">
                        <div class="ui-context character-select" id="context-2">
                            <div class="context">
                                <a class="close" href="javascript:;" onclick=
                                "return CharSelect.close(this);"></a>

                                <div class="context-user">
                                    <strong>{$User.username}</strong>
                                </div>

                                <div class="context-links">
                                    <a class="icon-profile link-first" href="/account/profile/{$User.username}" rel="np"
                                       title="{#Profile#}">
                                        <span class="context-icon"></span>{#Profile#}</a>
                                    <a class="icon-posts" href="/search?f=post&amp;a={$User.username}&amp;sort=time" rel="np" title="{#Posts_My#}">
                                        <span class="context-icon"></span>
                                    </a>
                                    <a class="icon-auctions" href="/vault/character/auction/" rel="np" title="{#Auction_View#}">
                                        <span class="context-icon"></span>
                                    </a>
                                    <a class="icon-events link-last" href="/vault/character/event" rel="np" title="{#Events_View#}">
                                        <span class="context-icon"></span>
                                    </a>
                                </div>
                            </div>

                        </div><a class="context-link wow-class-1" href="/account/profile/{$User.username}">
                            <span class="poster-name">{$User.username}</span>
                        </a>
                    </div>
                </div>

                <div class="text-wrapper">
                    <div class="input-wrapper">
                        <textarea class="input textarea" name="detail"></textarea>
                    </div>

                    <ul class="comments-error-form">
                        <li class="error-required">Обязательное для заполнения
                            поле</li>

                        <li class="error-throttled">Вы сейчас не можете размещать
                            сообщения</li>

                        <li class="error-length">Превышено максимальное количество
                            символов</li>

                        <li class="error-title">Учетная запись заблокирована на
                            форумах</li>

                        <li class="error-frozen">Срок действия игровой лицензии истек
                            или нет текущей подписки.</li>

                        <li class="error-locked">Возможность размещения сообщения с
                            этой учетной записи была отключена.</li>

                        <li class="error-cancelled">Срок действия игровой лицензии
                            истек или лицензия была отменена.</li>

                        <li class="error-trial">На этом форуме нельзя размещать и
                            оценивать сообщения со стартовой учетной записи. Конвертируйте
                            учетную запись в полную, чтобы активировать эти функции.</li>

                        <li class="error-unknown">Произошла ошибка. Попробуйте,
                            пожалуйста, еще раз. Но сначала выйдите из системы и
                            авторизуйтесь заново.</li>
                    </ul>

                    <div class="comments-action">
                        <button class="ui-button button1 comment-submit" onclick="return Comments.add(this);" type="button">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Messages_Send#}
                                </span>
                            </span>
                        </button>
                    </div>

                    <div class="comments-throttler">
                        {#Comments_Wait_Till_Next#}<span class="throttle-time">60</span>
                    </div>
                </div>
            </form>
        </div>
    {/if}