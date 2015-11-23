{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <script type="text/javascript" src="/Templates/{$Template}/js/account/services.js"></script>
            <script type="text/javascript" src="/Templates/{$Template}/js/account/realm-select.js"></script>
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
                                    <div class="character-icon wow-portrait-64 no-character">
                                    </div>
                                    <div class="service-tag-description">
                                        <span class="character-message caption">{#Account_Management_Service_Select_Character#}</span>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            </div>
                            <div class="service-summary">
                                <p class="service-price headline">{#Account_Management_Service_Price#}<br /> {$Service.price|string_format:"%.2f"} USD
                                </p>
                                <p>{#Account_Management_Service_Warning#}</p>
                                {if $Service.name == 'pnc'}
                                    <div class="service-tag-contents border-3 margin-top-20">
                                        <h2 class="caption">{#Account_Management_Service_Change_Name_Free#}:</h2>
                                        <ul class="arrow-list">
                                            <li><a href="/account/management/services/character-services?accountName=WoW{$Account.id}&service=PCC">{#Account_Management_Service_PCC#}</a></li>
                                            <li><a href="/account/management/services/character-services?accountName=WoW{$Account.id}&service=PRC">{#Account_Management_Service_PRC#}</a></li>
                                            <li><a href="/account/management/services/character-services?accountName=WoW{$Account.id}&service=PFC">{#Account_Management_Service_PFC#}</a></li>
                                        </ul>
                                    </div>
                                {/if}
                            </div>
                        </div>
                        <div class="service-form">
                            <div class="character-list">
                                <ul id="character-list">
                                    {foreach $Characters as $Character}
                                        <li class="character border-4" id="WoW{$Account.id}:{$Character.guid}:{$Character.name}">
                                            <div class="character-icon wow-portrait-64-80 wow-{$Character.gender}-{$Character.race}-{$Character.class} glow-shadow-3">
                                                <img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" width="64" height="64" alt="" />
                                            </div>
                                            <div class="character-description">
                                            <span class="character-name caption">
                                                {if $Character.online}
                                                    {$Character.name} {if $Character.guild_name != ''}- <em>{$Character.guild_name}</em>{/if}
                                                {else}
                                                    {if $Service.service == 'PCB'}
                                                        {if $Character.level != 80}
                                                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=tos&amp;character={$Character.name}" class="character-link">
                                                                {$Character.name} {if $Character.guild_name != ''}- <em>{$Character.guild_name}</em>{/if}
                                                            </a>
                                                    {else}
                                                        {$Character.name} {if $Character.guild_name != ''}- <em>{$Character.guild_name}</em>{/if}
                                                        {/if}
                                                {else}
                                                    <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=tos&amp;character={$Character.name}" class="character-link">
                                                        {$Character.name} {if $Character.guild_name != ''}- <em>{$Character.guild_name}</em>{/if}
                                                    </a>
                                                    {/if}
                                                {/if}
                                            </span>
                                            <span class="character-class" style="width: 400px;">
                                            {if $Character.online}
                                                <span style="font-weight: bold; color: red;">{#Account_Management_Service_Error_20034Title#}</span><br />
                                            {else}
                                                {if $Character.level == 80}
                                                    <span style="font-weight: bold; color: red;">{#Account_Management_Service_PCB_MaxLevel#}</span><br />
                                                {/if}
                                            {/if}
                                            {$Character.race_name}-{$Character.class_name} {$Character.level} {#LevelShort#}
                                            </span>
                                            </div>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                            <div id="error-container" style="display: none;"></div>
                            <script type="text/javascript">
                                //<![CDATA[
                                var additionalMessages = {
                                    'error': {
                                        'title': '{#Account_Management_Service_Error#}:',
                                        'serverTitle': '{#Account_Management_Service_World_Closed#}',
                                        'serverDesc': '{#Account_Management_Service_CheckBackLater#}',
                                        'retry': '<a href="#retry" onclick="return false;">{#Account_Management_Try_Again#}</a>',
                                        'multiDesc': '{#Account_Management_Service_Need_To_Solve#}',
                                        '20012Title': '{#Account_Management_Service_Error_20012Title#}',
                                        '20012Desc': '{#Account_Management_Service_Error_20012Desc#}',
                                        '20016Title': '{#Account_Management_Service_Error_20016Title#}',
                                        '20016Desc': '{#Account_Management_Service_Error_20016Desc#}',
                                        '20032Title': '{#Account_Management_Service_Error_20032Title#}',
                                        '20032Desc': '{#Account_Management_Service_Error_20032Desc#}',
                                        '20033Title': '{#Account_Management_Service_Error_20033Title#}',
                                        '20033Desc': '{#Account_Management_Service_Error_20033Desc#}',
                                        '20034Title': '{#Account_Management_Service_Error_20034Title#}',
                                        '20034Desc': '{#Account_Management_Service_Error_20034Desc#}',
                                        '20057Title': '{#Account_Management_Service_Error_20057Title#}',
                                        '20057Desc': '{#Account_Management_Service_Error_20057Desc#}',
                                        '20064Title': '{#Account_Management_Service_Error_20064Title#}',
                                        '20064Desc': '{#Account_Management_Service_Error_20064Desc#}',
                                        'unknown': '{#Account_Management_Service_Error_unknown#}'
                                    },
                                    'loading': {
                                        'title': '{#Account_Management_Service_Verifying#}'
                                    },
                                    'active': {
                                        serviceName: '{$Service.service}',
                                        viewingRealm: '0'
                                    }
                                };
                                //]]>
                            </script>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    $(".realmselect-dialog").dialog("destroy");
                    $(".realmselect-dialog").dialog({
                        "autoOpen": false,
                        "modal": true,
                        "position": "center",
                        "resizeable": false,
                        "closeText": "Закрыть",
                        "width": 570,
                        "height": 580
                    });
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}