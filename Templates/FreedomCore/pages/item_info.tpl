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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Game#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/item" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Item_Category#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/item/?classId={$Item.class.class}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Item.class.translation}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/item/?classId={$Item.class.class}&subClassId={$Item.subclass.subclass}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Item.subclass.translation}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/item/{$Item.entry}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Item.name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki wiki-item">
                <div class="sidebar">
                    {if $Item.class.class == 2 || $Item.class.class == 4 || $Item.class.class == 15 && $Item.subclass.subclass == 5}
                        {if $Item.subclass.subclass != 0}
                            <div class="snippet">
                                <div class="model-viewer">
                                    <div class="model  can-drag" id="model-{$Item.entry}">
                                        <div class="loading">
                                            {if $Item.class.class == 15 && $Item.subclass.subclass == 5}
                                                {for $i = 1; $i <= 5; $i++}
                                                    {$Data = 'spell_data'|cat:$i}
                                                    {if isset($Item.$Data.SearchForCreature)}
                                                        <div class="viewer" style="background-image: url('/Uploads/Core/Items/Cache/ModelViewer/creature{$Item.$Data.SearchForCreature}.jpg');"></div>
                                                    {/if}
                                                {/for}
                                            {else}
                                                <div class="viewer" style="background-image: url('/Uploads/Core/Items/Cache/ModelViewer/item{$Item.entry}.jpg');"></div>
                                            {/if}
                                        </div>
                                        <a href="javascript:;" class="rotate"></a>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    //<![CDATA[
                                    $(function() {
                                        Item.model = ModelRotator.factory("#model-{$Item.entry}", {
                                            zoom: false
                                        });
                                    });
                                    //]]>
                                </script>
                            </div>
                        {/if}
                    {/if}
                    {if $Item.RequiredDisenchantSkill != 0}
                        <div class="snippet">
                            <h3>{#Item_Info_Interesting_Fact#}</h3>
                            <ul class="fact-list">
                                <li>
                                    <span class="term">{#Item_Disenchanting#}:</span>
                                    {#Item_Required#} <a href="/game/profession/enchanting">{#Character_Professions_Enchanting#}</a> ({$Item.RequiredDisenchantSkill})
                                </li>
                            </ul>
                        </div>
                    {/if}
                    <div class="snippet">
                        <h3>{#Game_LearnMore#}</h3>

                        <span id="fansite-links" class="fansite-group">
                            <a href="http://{$Language}.wowhead.com/item={$Item.entry}" target="_blank">Wowhead</a>
                        </span>
                    </div>
                </div>
                <div class="info">
                    <div class="title">
                        <h2 class="color-q{$Item.Quality}">{$Item.name}</h2>
                    </div>
                    <div class="item-detail">
                        <span class="icon-frame frame-56 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/large/{$Item.icon}.jpg&quot;);"></span>
                        {assign "ItemInfoPage" 'true'}
                        {include file='blocks/item_tooltip.tpl'}
                    </div>
                </div>
                <span class="clear"><!-- --></span>
                <div class="related">
                    <div class="tabs ">
                        <ul id="related-tabs">
                            {if isset($ItemRelation.vendors)}
                                <li >
                                    <a href="#vendors" data-key="vendors" id="tab-vendors" class="tab-active">
										<span><span>
												{#Item_Vendors#}:
												(<em>{count($ItemRelation.vendors)}</em>)
										</span></span>
                                    </a>
                                </li>
                            {/if}
                            {if isset($ItemRelation.rewardFromQuests)}
                                <li>
                                    <a href="#rewardFromQuests" data-key="rewardFromQuests" id="tab-rewardFromQuests" class="tab-active">
										<span><span>
												{#Item_Reward_Quest#}
                                                (<em>{count($ItemRelation.rewardFromQuests)}</em>)
										</span></span>
                                    </a>
                                </li>
                            {/if}
                            <li>
                                <a href="#comments" data-key="comments" id="tab-comments">
										<span><span>
												{#Comments#}
												(<em>{count($ItemRelation.comments)}</em>)
										</span></span>
                                </a>
                            </li>
                        </ul>

                        <span class="clear"><!-- --></span>
                    </div>

                    <div id="related-content" class="loading">
                    </div>
                    <script type="text/javascript">
                        //<![CDATA[
                        $(function() {
                            Wiki.pageUrl = '/item/{$Item.entry}/';
                        });
                        //]]>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}