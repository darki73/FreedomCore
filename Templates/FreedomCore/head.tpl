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
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/wow.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/lightbox.css" />
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
    {/if}
    {if $Page.bodycss == 'realm-status'}
        <link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/realmstatus.css" />
    {/if}
	{if $Page.type == "homepage" || $Page.type == "blog" || $Page.type == 'community' && $Page.bodycss == 'community-home'}
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/build/cms.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/Templates/{$Template}/css/cms.css" />
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
</head>