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
                        <h3 class="headline">{#Account_Management_Service_PFC#}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC" class="active">{#Account_Management_Service#}</a>
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=history">{#Account_Management_Service_History#}</a>
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
                            <div class="service-interior">
                                <h2 class="caption">{#Account_Management_Service_ToS_Limitations#}</h2>
                                <div class="tos-left full-width">
                                    <ul>
                                        <li>{#Account_Management_Service_PFC_ToS_One#}</li>
                                        <li>{#Account_Management_Service_PFC_ToS_Two#}</li>
                                        <li>{#Account_Management_Service_PFC_ToS_Free#}</li>
                                        <li>{#Account_Management_Service_PFC_ToS_Four#}</li>
                                    </ul>
                                </div>
                                <span class="clear"></span>
                                <form method="POST" action="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=confirm&amp;character={$Character.name}">
                                    <fieldset class="ui-controls section-stacked">
                                        <button class="ui-button button1" type="submit" id="tos-submit" tabindex="1">
                                            <span class="button-left">
                                                <span class="button-right">{#Account_Management_Service_ToS_Accept#}</span>
                                            </span>
                                        </button>
                                        <a class="ui-cancel " href="/account/management/services/character-services?accountName=WoW{$Account.id}&service=PFC" tabindex="1">
                                            <span>
                                                {#Account_Management_Service_ToS_Decline#}
                                            </span>
                                        </a>
                                    </fieldset>
                                    <script type="text/javascript">
                                        //<![CDATA[
                                        (function() {
                                            var tosSubmit = document.getElementById('tos-submit');
                                            tosSubmit.removeAttribute('disabled');
                                            tosSubmit.className = 'ui-button button1';
                                        })();
                                        //]]>
                                    </script>
                                </form>
                            </div>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}