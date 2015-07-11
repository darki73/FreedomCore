{include file = 'installation/head.tpl'}
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a href="#" class="brand">
                <img src="/Templates/{$Template}/images/logos/bnet-default.png" width="300" height="118" alt="Logo" />
                <!-- This is website logo -->
            </a>
            <!-- Navigation button, visible on small resolution -->
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <i class="icon-menu"></i>
            </button>
            <!-- Main navigation -->
            <div class="nav-collapse collapse pull-right">
                <ul class="nav" id="top-navigation">
                    <li class="active"><a href="#home">{#Installation_Main#}</a></li>
                    <li><a href="#sysreq">{#Installation_SysReq#}</a></li>
                    <li><a href="#installation">{#Installation_Installation#}</a></li>
                    <li><a href="http://github.com/darki73/FreedomCore" target="_blank">Github</a></li>
                    <li><a href="http://github.com/darki73/FreedomCore/issues" target="_blank">Issues Tracker</a></li>
                </ul>
            </div>
            <!-- End main navigation -->
        </div>
    </div>
</div>