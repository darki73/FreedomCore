<div class="sidebar-module" id="sidebar-gear-store">
    {foreach $Realms as $Realm}
    <div class="sts a-realm sidebar-container box-shadow">
        <div id="head" class="clearfix text-shadow">
            <p id="name">{$Realm.name}</p>
            <p id="info">{if $Realm.status == 'down'}<font color="red">Offline</font>{else}<font color="green">Online</font>{/if}</p>
        </div>
        <div id="containerbody" class="clearfix text-shadow">
            <p id="online"><font color="#d28010">{$Realm.online}</font> {#Players_Online#}</p>
            <p id="uptime"><font color="#5b5851">{$Realm.uptime}</font></p>
        </div>
    </div>
    {/foreach}
    <div class="realmlist realm_cont_show">
        <p>set realmlist <font color="#817464">{$smarty.server.HTTP_HOST}</font></p>
    </div>
</div>