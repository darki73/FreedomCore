<div class="sidebar-module" id="sidebar-gear-store">
	<div class="sidebar-title">
		<h3 class="header-3 title-gear-store">{#Realm_Status_Title#}</h3>
	</div>

	<div class="sidebar-content">

        <ul class="articles-list-plain">
            {foreach $Realms as $Realm}
                <li>
                    <strong>Realm Name: </strong> {$Realm.name}<br />
                    {if $Realm.status == 'down'}
                        Server is Offline
                    {else}
                        Server is Online
                    {/if}
                </li>
            {/foreach}
        </ul>
	</div>
</div>