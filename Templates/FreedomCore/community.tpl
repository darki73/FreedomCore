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
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/community/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Community#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <script type="text/javascript">
                //<![CDATA[
                $(document).ready(function(){
                    Input.bind('#wowcharacter');
                    Input.bind('#wowguild');
                });
                //]]>
            </script>
            <div id="left">
                <div class="profiles">
                    <h4>{#Menu_Community#}</h4>
                    <div class="profile-section">
                        <div class="sidebar-module " id="sidebar-profiles-search">
                            <div class="sidebar-title">
                                <h3 class="header-3 title-profiles-search">
                                    {#Community_Profiles#}
                                </h3>
                            </div>

                            <div class="sidebar-content">
                                <div class="profiles-search-block">
                                    <span class="profiles-search-title">{#Community_Character#}</span>
                                    <form action="/search" method="get">
                                        <input type="hidden" name="f" value="wowcharacter" />
                                        <input type="text" id="wowcharacter" alt="{#Community_Name#}" name="q" />

                                        <button class="ui-button button1" type="submit"><span class="button-left"><span class="button-right">{#Community_Search#}</span></span></button>
                                    </form>
                                </div>
                                <div class="profiles-search-block">
                                    <span class="profiles-search-title">{#Community_Guild#}</span>
                                    <form action="/search" method="get">
                                        <input type="hidden" name="f" value="wowguild" />
                                        <input type="text" id="wowguild" alt="{#Community_Name#}" name="q" />

                                        <button class="ui-button button1" type="submit"><span class="button-left"><span class="button-right">{#Community_Search#}</span></span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <p class="profiles-tip">
                            {#Community_LogIn_For_Profile#}
                        </p>
                    </div>
                </div>
                <div class="main-feature">
                    <div class="main-feature-wrapper">
                        <div class="sidebar-module" id="sidebar-leaderboards">
                            <div class="sidebar-title">
                                <h3 class="header-3 title-leaderboards">
                                    {#Community_Ratings#}
                                </h3>
                            </div>
                            <div class="sidebar-content">
                                <div id="challenge-mode" class="leaderboard-content-block">
                                    <a href="/challenge/dungeon/nei" class="leaderboard-content-title">{#Community_CM#}</a>
                                    <span class="leaderboard-content-desc">{#Community_CM_Desc#}</span>
                                </div>
                                <div id="pvp-ladder" class="leaderboard-content-block">
                                    <a href="/pvp/leaderboards/3v3" class="leaderboard-content-title">{#Community_PVPRatings#}</a>
                                    <span class="leaderboard-content-desc">{#Community_PVPRatings_Desc#}</span>
                                    <div class="group">
                                        <a href="/pvp/leaderboards/rbg">
                                            <span class="group-thumbnail thumb-battlegrounds"></span>
									        <span class="group-name">
                                                {#Community_RBG#}
									        </span>
                                            <span class="clear"><!-- --></span>
                                        </a>
                                        <a href="/pvp/leaderboards/2v2">
                                            <span class="group-thumbnail thumb-arena-2v2"></span>
									        <span class="group-name">
                                                2 vs 2
									        </span>
                                            <span class="clear"><!-- --></span>
                                        </a>
                                        <a href="/pvp/leaderboards/3v3">
                                            <span class="group-thumbnail thumb-arena-3v3"></span>
                                            <span class="group-name">
                                                3 vs 3
                                            </span>
                                            <span class="clear"><!-- --></span>
                                        </a>
                                        <a href="/pvp/leaderboards/5v5">
                                            <span class="group-thumbnail thumb-arena-5v5"></span>
                                            <span class="group-name">
                                                5 vs 5
                                            </span>
                                            <span class="clear"><!-- --></span>
                                        </a>
                                    </div>
                                </div>
                                <span class="clear"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="right">
                <div class="sidebar-module " id="sidebar-game-realms">
                    <div class="sidebar-title">
                        <h3 class="header-3 title-game-realms">
                            {#Community_Realms_Status#}
                        </h3>
                    </div>
                    <div class="sidebar-content">
                        <ul>
                            <li>
                                <a href="/community/status" class="realm-status block">
                                    <span class="content-icon"></span>
                                    <span class="content-title">{#Community_Realms_Status_Title#}</span>
                                    <span class="content-desc">{#Community_Realms_Status_Desc#}</span>
                                    <span class="clear"><!-- --></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</div>
{include file="footer.tpl"}