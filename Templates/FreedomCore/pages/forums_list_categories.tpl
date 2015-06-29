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
                    <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/forum" rel="np" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{#Forum_Page_Title#}</span>
                        </a>
                    </li>
                </ol>
            </div>
            <div class="content-bot clear">
                <div id="freedom-tracker-light">

                </div>
                <span class="clear"></span>
                <div id="station-content">
                    <div class="station-content-wrapper">
                        <div class="station-inner-wrapper">
                            <div class="right-column">

                            </div>
                            <div class="left-column" id="forum-list">
                                <div class="forum-list-interior">
                                {foreach from=$Forums item=Forum key=i}
                                    <div class="forum-group">
                                        <h2 class="header-2">
                                            <a id="forum{$Forum.0.forum_id}" href="javascript:;" onclick="Station.toggleForumGroup('{$Forum.0.forum_id}', this)" class="group-header">
                                                {$Forum.0.forum_type_name}
                                            </a>
                                        </h2>
                                        <ul class="child-forums" id="child{$Forum.0.forum_id}">
                                            {foreach $Forum as $Category}
                                                {if $i < 5}
                                                    <li class="child-forum">
                                                        <a href="{$Category.forum_id}/" class="forum-link">
                                                        <span class="forum-icon">
                                                                <img src="/Templates/{$Template}/images/forum/icons/{$Category.forum_icon}" />
                                                        </span>
                                                        <span class="forum-details">
                                                            <span class="forum-title">
                                                                {$Category.forum_name}
                                                                <span class="forum-status"></span>
                                                            </span>
                                                            <span class="forum-desc">
                                                                {$Category.forum_description}
                                                            </span>
                                                        </span>
                                                        </a>
                                                    </li>
                                                {else}
                                                    <li class="child-forum child-forum-compact">
                                                        <a href="{$Category.forum_id}/" class="forum-link">
                                                            <span class="forum-icon">
                                                                    <img src="/Templates/{$Template}/images/forum/icons/{$Category.forum_icon}" />
                                                            </span>
                                                            <span class="forum-details">
                                                                <span class="forum-title">
                                                                    {$Category.forum_name}
                                                                    <span class="forum-status"></span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                {/foreach}
                                </div>
                            </div>
                            <span class="clear"></span>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function(){
                        Station.initialize();
                    })
                </script>
            </div>
        </div>
    </div>
{include file = 'footer.tpl'}