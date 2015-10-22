{include file = 'installation/step_header.tpl'}
<div id="wrap">
    <div class="container">
        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-8 col-md-7 col-sm-6">
                    <h1>{#Installation_SysReq#}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{#Installation_Module#}</th>
                        <th>{#Installation_Module_Required#}</th>
                        <th>{#Installation_Module_Installed#}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr {if $ServerInfo.PHP.valid == "VALIDATION_STATE_PASSED"} style="background-color: lightgreen;"{elseif $ServerInfo.PHP.valid == "VALIDATION_STATE_WARNING"} style="background-color: orange;"{else} style="background-color: red;"{/if}>
                        <td>{#Installation_Module_PHP#}</td>
                        <td>{$ServerInfo.PHP.required}</td>
                        <td>{$ServerInfo.PHP.installed}</td>
                    </tr>
                    <tr {if $ServerInfo.MySQL.valid == "VALIDATION_STATE_PASSED"} style="background-color: lightgreen;"{elseif $ServerInfo.MySQL.valid == "VALIDATION_STATE_WARNING"} style="background-color: orange;"{else} style="background-color: red;"{/if}>
                        <td>{#Installation_Module_MySQL#}</td>
                        <td>{$ServerInfo.MySQL.required}</td>
                        <td>{$ServerInfo.MySQL.installed}</td>
                    </tr>
                    <tr {if $ServerInfo.Apache.valid == "VALIDATION_STATE_PASSED"} style="background-color: lightgreen;"{elseif $ServerInfo.Apache.valid == "VALIDATION_STATE_WARNING"} style="background-color: orange;"{else} style="background-color: red;"{/if}>
                        <td>{#Installation_Module_Apache#}</td>
                        <td>{$ServerInfo.Apache.required}</td>
                        <td>{$ServerInfo.Apache.installed}</td>
                    </tr>
                    <tr {if $ServerInfo.OS.valid == "VALIDATION_STATE_PASSED"} style="background-color: lightgreen;"{elseif $ServerInfo.OS.valid == "VALIDATION_STATE_WARNING"} style="background-color: orange;"{else} style="background-color: red;"{/if}>
                        <td>{#Installation_Module_OS#}</td>
                        <td>{$ServerInfo.OS.required}</td>
                        <td>{$ServerInfo.OS.installed.name} {$ServerInfo.OS.installed.version} ({$ServerInfo.OS.installed.build})</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{#Installation_Module#}</th>
                        <th>{#Installation_Module_Status#}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $Modules as $Module}
                        <tr {if $Module.status == "1"} style="background-color: lightgreen;" {else} style="background-color: red;"{/if}>
                            <td>{$Module.name}</td>
                            {if $Module.status == "1"}
                                <td>{#Installation_Module_Si#}</td>
                            {else}
                                <td>{#Installation_Module_No#}</td>
                            {/if}
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
            <center>
                {if $ServerInfo.AllowInstallation == "NO"}
                <span class="btn btn-primary" disabled="disabled"> {#Installation_Setup_Database#}</span>
                {else}
                <a href="/Install/database" class="btn btn-primary"> {#Installation_Setup_Database#}</a>
                {/if}
            </center>
        </div>
    </div>
</div>
{include file = 'installation/step_footer.tpl'}