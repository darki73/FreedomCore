{include file = 'installation/header.tpl'}
<div class="tm-section tm-section-color-1 tm-section-colored">
    <div class="uk-container uk-container-center uk-text-center">

        <img class="tm-logo" src="/Templates/FreedomCore/images/logos/bnet-default.png" width="281" height="217" title="{$AppName}" alt="{$AppName}">

        <p class="uk-text-large">

        </p>

        <a href="/Install/begin" class="uk-button tm-button-download">{#Installation_Perform_Installation#}</a>
        <a href="{$Github.url}" class="uk-button tm-button-download" target="_blank">Github</a>

        <ul class="tm-subnav uk-subnav">
            <li><strong>Last Update:</strong> {$Github.last_update|date_format:"%e %B %Y %H:%M"}<br />
                <strong>Last Rev: <a href="{$Github.url}/commit/{$Github.commit}" target="_blank" style="color: gold!important;">{$Github.commit}</a></strong>
            </li>
        </ul>

        <ul class="tm-subnav uk-subnav">
            <li><a href="{$Github.stargazers_url}" target="_blank"><i class="uk-icon-star"></i> <span data-uikit-stargazers="">{$Github.stargazers}</span> Stargazers</a></li>
            <li><a href="{$Github.forks_url}" target="_blank"><i class="uk-icon-code-fork"></i> <span data-uikit-forks="">{$Github.forks}</span> Forks</a></li>
            <li><a href="https://twitter.com/zhivolupov" target="_blank"><i class="uk-icon-twitter"></i> @zhivolupov</a></li>

        </ul>

    </div>
</div>

{include file = 'installation/footer.tpl'}