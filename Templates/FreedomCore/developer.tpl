{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">FreedomCore</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/api/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Developer_Page_Title#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="left">
                <div class="main-feature">
                    <div class="main-feature-wrapper">
                        <div class="sidebar-module" id="sidebar-leaderboards">
                            <div class="sidebar-title">
                                <h3 class="header-3 title-leaderboards">
                                    {$AppName} {#Developer_API_Header#}
                                </h3>
                            </div>
                            <div class="sidebar-content">
                                <ul style="margin-top:25px;">
                                    {include file = 'developer/api_armory.tpl'}
                                    <br />
                                    {include file = 'developer/api_account.tpl'}
                                    <br />
                                    {include file = 'developer/api_achievement.tpl'}
                                    <br />
                                    {include file = 'developer/api_item.tpl'}
                                </ul
                                <span class="clear"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $(".endpoint_accordion h3").click(function()
                    {
                        $(".endpoint_accordion ul").slideUp();
                        if(!$(this).next().is(":visible"))
                        {
                            $(this).next().slideDown();
                        }
                    })
                });
            </script>
            <div id="right">
                <div class="sidebar-module">
                    <div class="sidebar-title">
                        <h3 class="header-3 title-api-key">
                            {#Developer_API_Settings#}
                        </h3>
                    </div>
                    <div class="sidebar-content">
                        <div class="sts a-realm sidebar-container box-shadow">
                                {if isset($UserAPIKey) && $UserAPIKey != false}
                                    <div id="head" class="clearfix text-shadow">
                                        <p id="name">{#Developer_API_Key#}:</p>
                                    </div>
                                    <div id="containerbody" class="clearfix text-shadow">
                                        <input type="text" id="apikey" value="{$UserAPIKey}" class="search-field input" style="width:98%; margin: 5px 50px 5px 5px;text-align:center;" readonly>
                                    </div>
                                {elseif isset($UserAPIKey) && $UserAPIKey == false}
                                        <div id="head" class="clearfix text-shadow">
                                            <p id="name">{#Developer_API_Key#}:</p>
                                        </div>
                                        <div id="containerbody" class="clearfix text-shadow">
                                            <button id="getapikeybutton" class="ui-button button1" type="submit" style="width:100%">
                                                <span class="button-left" style="width:100%;padding: 0;">
                                                    <span class="button-right" style="width:100%;padding: 0;">
                                                        {#Developer_API_Key_Get#}
                                                    </span>
                                                </span>
                                            </button>
                                            <input type="text" id="apikey" value="{$UserAPIKey}" class="search-field input" style="width:98%; margin: 5px 50px 5px 5px;text-align:center;" readonly>
                                            <script type="text/javascript">
                                                $( document ).ready(function() {
                                                    $('#apikey').hide();
                                                });
                                                $("#getapikeybutton").click(function() {
                                                    $.ajax({
                                                        type: 'post',
                                                        url: "/api/key/generate",
                                                        data: "username={$User.username}",
                                                        complete: function (response) {
                                                            $('#getapikeybutton').delay(500).fadeOut(300);
                                                            $("#apikey").val(response.responseText);
                                                            $('#apikey').delay(500).fadeIn(300);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </div>
                                {else}
                                    <center>
                                        {#Developer_Authorization_Needed#}
                                        <input type="hidden" id="apikey" value="undefined" readonly>
                                    </center>
                                {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}