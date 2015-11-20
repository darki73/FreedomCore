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
                    <a href="/forum/#forum{$Forum.forum_type}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Forum.forum_type_name}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/forum/{$Forum.forum_id}/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Forum.forum_name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="topics-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog">
                <div class="forum-actions forum-actions-top">
                    <div class="inner-search-wrapper">
                        <form action="/search" class="inner-search-form" method="get">
                            <fieldset>
                                <input type="text" name="q" value="{#Forum_Search_This_Forum#}" alt="{#Forum_Search_This_Forum#}" id="inner-search-field" />
                                <input type="hidden" name="f" value="post" />
                                <input type="hidden" name="forum" value="{$Forum.forum_id}" />
                                <input type="submit" class="inner-search-submit" value=" " />
                            </fieldset>
                        </form>
                        <script type="text/javascript">
                            //<![CDATA[
                            $(function() { Input.bind('#inner-search-field'); });
                            //]]>
                        </script>
                    </div>
                    <h2 class="subheader-2" itemprop="headline">{$Forum.forum_name}</h2>
                    {if !$smarty.session.loggedin}
                    <div class="create-button-wrapper">
                        <a class="ui-button button1" href="/account/login">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Forum_Create_Topic#}
                                </span>
                            </span>
                        </a>
                    </div>
                    {else}
                        {if $Characters == 0}
                            <div class="create-button-wrappe">
                                <a class="ui-button button1 disabled" href="javascript:;" data-tooltip="{#Forum_Non_Characters#}">
                                    <span class="button-left">
                                        <span class="button-right">
                                            {#Forum_Create_Topic#}
                                        </span>
                                    </span>
                                </a>
                            </div>
                        {else}
                            <div class="create-button-wrapper">
                                <a class="ui-button button1" href="topic">
                                    <span class="button-left">
                                        <span class="button-right">
                                            {#Forum_Create_Topic#}
                                        </span>
                                    </span>
                                </a>
                            </div>
                        {/if}
                    {/if}
                    <div class="paging-wrapper">

                    </div>
                    <span class="clear"></span>
                </div>
                {if empty($Forum.topics)}
                    <center>{#Forum_No_Topics_Found#}</center>
                {else}
                    {include file='blocks/forum_topics_block.tpl'}
                {/if}
                <div class="forum-actions forum-actions-bottom">
                    {if !$smarty.session.loggedin}
                        <div class="create-button-wrapper">
                            <a class="ui-button button1" href="/account/login">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Forum_Create_Topic#}
                                </span>
                            </span>
                            </a>
                        </div>
                    {else}
                        {if $Characters == 0}
                            <div class="create-button-wrappe">
                                <a class="ui-button button1 disabled" href="javascript:;" data-tooltip="{#Forum_Non_Characters#}">
                                        <span class="button-left">
                                            <span class="button-right">
                                                {#Forum_Create_Topic#}
                                            </span>
                                        </span>
                                </a>
                            </div>
                        {else}
                            <div class="create-button-wrapper">
                                <a class="ui-button button1" href="topic">
                                <span class="button-left">
                                    <span class="button-right">
                                        {#Forum_Create_Topic#}
                                    </span>
                                </span>
                                </a>
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    Forums.initialize("#forum-topics", {$Forum.forum_id});
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file = 'footer.tpl'}