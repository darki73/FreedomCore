{include file = 'updater/header.tpl'}
<div id="wrap">
    <div class="container">
        <div class="page-header" id="banner">
            <div class="row">
                <div class="col-lg-8 col-md-7 col-sm-6">
                    <h1>{#Updater_Preparation#}</h1>
                </div>
            </div>
        </div>
        {if $UpdateData.downloaded}
            <div class="row">
                {if $UpdateData.updating_from.id != $UpdateData.updating_to.id}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{#Updater_Current_Version#}</th>
                                <th>{#Updater_New_Version#}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><strong>{#Updater_Update_ID#}</strong></td>
                                <td>{$UpdateData.updating_from.id}</td>
                                <td>{$UpdateData.updating_to.id}</td>
                            </tr>
                            <tr>
                                <td><strong>{#Updater_Update_Hash#}</strong></td>
                                <td>{$UpdateData.updating_from.sha}</td>
                                <td>{$UpdateData.updating_to.sha}</td>
                            </tr>
                            <tr>
                                <td><strong>{#Updater_Update_Date#}</strong></td>
                                <td>{$UpdateData.updating_from.date}</td>
                                <td>{$UpdateData.updating_to.date}</td>
                            </tr>
                            <tr>
                                <td><strong>{#Updater_Update_Title#}</strong></td>
                                <td>{$UpdateData.updating_from.title}</td>
                                <td>{$UpdateData.updating_to.title}</td>
                            </tr>
                            <tr>
                                <td><strong>{#Updater_Author#}</strong></td>
                                <td>{$UpdateData.updating_from.author}</td>
                                <td>{$UpdateData.updating_to.author}</td>
                            </tr>
                            {if $UpdateData.updating_to.post_install}
                            <tr style='background-color: orangered'>
                                <td><strong>{#Updater_Post_Install#}</strong></td>
                                <td colspan="2">
                                    <center>
                                        {#Updater_Post_Install_Required#}
                                    </center>
                                </td>
                            </tr>
                            {/if}
                            <tr {if $UpdateData.safe_update == 0}style='background-color: orangered'{else}style='background-color: forestgreen'{/if}">
                            <td><strong>{#Updater_Update_SafeUpdate#}</strong></td>
                            <td colspan="2">
                                <center>
                                    {if $UpdateData.safe_update == 0}
                                        {#Updater_SafeUpdate_No#}
                                    {else}
                                        {#Updater_SafeUpdate_Yes#}
                                    {/if}
                                </center>
                            </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                {/if}
                <center>
                    {if $UpdateData.updating_from.id == $UpdateData.updating_to.id}
                        <strong>{#Updater_Latest_Version#}<br />{#Updater_No_Update_Required#}</strong>
                    {else}
                        {if $UpdateData.safe_update == 0}
                            <div class="alert alert-warning">
                                <h3>{#Updater_Attention#}</h3>
                                {#Updater_UnsafeUpdate#}
                            </div>
                            {if $UpdateData.updating_to.post_install}
                                <a href="/Update/post_configuration" class="btn btn-primary"> {#Updater_Start_Update#}</a>
                            {else}
                                <a href="/Update/complete" class="btn btn-primary"> {#Updater_Start_Update#}</a>
                            {/if}
                        {else}
                            {if $UpdateData.updating_to.post_install}
                                <a href="/Update/post_configuration" class="btn btn-primary"> {#Updater_Start_Update#}</a>
                            {else}
                                <a href="/Update/complete" class="btn btn-primary"> {#Updater_Start_Update#}</a>
                            {/if}
                        {/if}
                    {/if}
                </center>
            </div>
        {else}
                <center style="margin-top: 50px; margin-bottom: 50px;">
                    <div class="alert alert-warning">
                        You need to use <strong> git pull </strong> to update files to latest version before running update
                    </div>
                </center>
        {/if}
    </div>
</div>
{include file = 'updater/footer.tpl'}