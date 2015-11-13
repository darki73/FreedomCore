{include file = 'installation/step_head.tpl'}
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="/Install" class="navbar-brand" style="padding: 10.5px;">
                <img src="/Templates/FreedomCore/images/logos/bnet-default.png" style="height: 50px; margin-top: -12px;">
            </a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class="nav-link active" href="/Install">
                        <i class="fa fa-home"></i>
                        {#Menu_Main#}
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{$Github.url}">
                        <i class="fa fa-github"></i>
                        Github
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="https://netdocs.freedomcore.ru">
                        <i class="fa fa-file-code-o"></i>
                        {#Installation_Documentation#}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>