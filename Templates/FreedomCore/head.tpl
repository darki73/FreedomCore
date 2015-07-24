<html xml:lang="{$Language}" class="{$Language}">
<head xmlns:og="http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
	<meta http-equiv="imagetoolbar" content="false" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>{$Page.pagetitle} {$AppName}</title>
	<link rel="shortcut icon" href="/Templates/{$Template}/images/meta/favicon.ico" />
	<link rel="search" type="application/opensearchdescription+xml" href="http://{$smarty.server.HTTP_HOST}/data/opensearch" title="{#Head_Opensearch_Meta#} {$AppName}" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/common-game-site.css" />
	<link title="{$AppName}® - {#Head_News_Meta#}" href="/feed/news" type="application/atom+xml" rel="alternate"/>
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/expansion-{$ExpansionTemplate}.css" />
    {if $ExpansionTemplate == 'WotLK'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wow-wotlk.css" />
    {else}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wow.css" />
    {/if}
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/nav-client-desktop.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/lightbox.css" />
    {if $Page.type == 'forum'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/build/cms.min.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/forums/forums-index.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/forums/view-forum.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/forums/view-topic.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/forums/freedom-tracker.css" />
    {/if}
    {if $Page.type == "community" && $Page.bodycss == 'community-home'}
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/community.css" />
    {/if}
    {if $Page.bodycss|strstr:"item-"}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/wiki.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/item.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/comments.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/cms.css" />
    {/if}
    {if $Page.type == 'zone'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/wiki.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/zone.css" />
        {if $Page.bodycss|strstr:'boss-'}
            <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/boss.css" />
        {/if}
        <style type="text/css">
            #content .content-top { background: url("/Templates/{$Template}/images/wiki/zone/bgs/{$ZoneInfo.link_name}.jpg") 0 0 no-repeat; }
        </style>
    {/if}
    {if $Page.bodycss == 'realm-status'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/realmstatus.css" />
    {/if}
	{if $Page.type == "homepage" || $Page.type == "blog" || $Page.type == 'community' && $Page.bodycss == 'community-home'}
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/build/cms.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/cms.css" />
    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/sidebar.css" />
	{/if}
    {if $Page.type == "shop" || $Page.bodycss == "services-home"}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/shop-index.css" />
    {/if}
	{if $Page.type == "game"}
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/wiki.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/game-common.css" />
    {if $Page.bodycss == "game-index"}
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/game-index.css" />
	{else if $Page.bodycss == "game-race-index" || isset($Race.race)}
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/game-race-index.css" />
	{else if $Page.bodycss == "game-classes-index" || isset($Class.class_name)}
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/game-class-index.css" />
	{else if $Page.bodycss == "game-patchnotes"}
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/patch-notes-meta.css" />
		<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/game/game-patchnotes.css" />
		<style type="text/css">
		.body-top { background:url('/Uploads/cms/gallery/E5RBK25967ZM1423181836865.jpg') 0 0 no-repeat }
		</style>
	{/if}
	{/if}
    {if $Page.type == "search"}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/search-common.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/search.css" />
    {/if}
    {if $Page.type == "profession"}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/wiki.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/profession.css" />
    {/if}
    {if $Page.type == 'community' && $Page.bodycss == 'profile_page'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/zone.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/character/summary.css" />
        <style type="text/css">
            #content .content-top { background: url("/Templates/{$Template}/images/character/summary/backgrounds/race/{$Character.race}.jpg") left top no-repeat; }
            .profile-wrapper { background-image: url("/Templates/{$Template}/images/2d/profilemain/race/{$Character.race}-{$Character.gender}.jpg"); }
        </style>

    {/if}
    {if $Page.type == 'community' && $Page.bodycss == 'character-pvp'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/arena/arena.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/arena/pvp.css" />
    {/if}
    {if $Page.bodycss == 'achievement_page'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/character/achievement.css" />
    {/if}
    {if $Page.bodycss == 'guild_page'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/guild/guild.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/guild/summary.css" />
    {/if}
    {if $Page.bodycss == 'reputation_page'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/character/reputation.css" />
    {/if}
    {if $Page.bodycss == 'professions_page'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/profile.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/wiki.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wiki/profession.css" />
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/character/profession.css" />
    {/if}

    <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/locale/{$Language}.css" />
	
	<script type="text/javascript" src="/Templates/{$Template}/js/third-party.js"></script>
	<script type="text/javascript" src="/Templates/{$Template}/js/common-game-site.js"></script>
	<meta name="description" content="{$AppDescription}" />
	<script type="text/javascript">
	//<![CDATA[
		var Core = Core || {},
		Login = Login || {};
		Core.staticUrl = '/Templates/{$Template}';
		Core.sharedStaticUrl = '/Templates/{$Template}';
		Core.baseUrl = 'http://{$smarty.server.HTTP_HOST}';
		Core.projectUrl = '/';
		Core.cdnUrl = '/';
		Core.supportUrl = '';
		Core.secureSupportUrl = '';
		Core.project = 'wow';
		Core.locale = '{$Language}';
		Core.language = '{$Language}';
		Core.region = 'eu';
		Core.shortDateFormat = 'dd/MM/yyyy';
		Core.dateTimeFormat = 'dd/MM/yyyy HH:mm';
		Core.loggedIn = false;
		Core.userAgent = 'web';
		Login.embeddedUrl = '/fragment/login.frag';
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '{$GoogleAnalytics.Account}']);
		_gaq.push(['_setDomainName', '{$GoogleAnalytics.Domain}']);
		_gaq.push(['_setAllowLinker', true]);
		_gaq.push(['_trackPageview']);
		//]]>
	</script>
    {if $GoogleAnalytics.Account != ''}
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '{$GoogleAnalytics.Account}', 'auto');
            ga('send', 'pageview');

        </script>
    {/if}
</head>