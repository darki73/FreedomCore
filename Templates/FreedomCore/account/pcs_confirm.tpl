{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{#Account_Management_Service_CS#}</h2>
                        <h3 class="headline">{$Service.title}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}" class="active">{#Account_Management_Service#}</a>
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=history">{#Account_Management_Service_History#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                        </p>
                        <div class="service-info">
                            <div class="service-tag">
                                <div class="service-tag-contents border-3">
                                    <div class="character-icon wow-portrait-64-80 wow-{$Character.gender}-{$Character.race}-{$Character.class} glow-shadow-3">
                                        <img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" width="64" height="64" alt="" />
                                    </div>
                                    <div class="service-tag-description">
                                        <span class="character-name caption">
                                            {$Character.name}
                                        </span>
                                        <span class="character-class">
                                            {#LevelShort#} {$Character.level} {$Character.race_data.translation} {$Character.class_data.translation}
                                        </span>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            </div>
                            <div class="service-summary">
                                <p class="service-price headline">{$Service.price|string_format:"%.2f"} USD
                                </p>
                                <p class="service-memo">{#Account_Management_Service_Waiting_Time#}</p>
                            </div>
                        </div>
                        <div class="service-form">
                            <form method="POST" action="/account/management/payment/pay?accountName=WoW{$Account.id}&character={$Character.name}&service={$Service.name}">
                            {if $Service.name == 'pfc'}
                                {include file='account/service_confirm/faction.tpl'}
                            {elseif $Service.name == 'prc'}
                                {include file='account/service_confirm/race.tpl'}
                            {elseif $Service.name == 'pcc'}
                                {include file='account/service_confirm/character.tpl'}
                            {elseif $Service.name == 'pnc'}
                                {include file='account/service_confirm/name.tpl'}
                            {elseif $Service.name == 'pcb'}
                                {include file='account/service_confirm/boost.tpl'}
                            {/if}
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}