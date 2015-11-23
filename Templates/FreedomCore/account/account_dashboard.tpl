{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard wowx{$Account.expansion} eu">
                <div class="primary">
                    <div class="header">
                        <h2 class="subcategory">{#Account_Management_Game_Management#}</h2>
                        <h3 class="headline">World of Warcraft®: {$Account.expansion_name}™</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}">
                            <img src="/Templates/{$Template}/images/game-icons/wowx5.png" title="" width="48" height="48" />
                        </a>
                    </div>
                    <div class="account-summary">
                        <div class="account-management">
                            <div class="section box-art" id="box-art">
                                <img src="/Templates/{$Template}/images/products/box-art/WoW{$Account.expansion}.png" alt="World of Warcraft" title="" width="242" height="288" id="box-img" />
                            </div>
                            <div class="section account-details">
                                <dl>
                                    <dt class="subcategory">{#Account_Management#}</dt>
                                    <dd class="account-name">WoW{$Account.id}</dd>
                                    <dt class="subcategory">{#Account_Management_Account_Status#}</dt>
                                    <dd class="account-status">
                                        <strong class="active">{#Account_Management_Account_Status_Active#}</strong>
                                    </dd>
                                    <dt class="subcategory">{#Account_Management_Game_Time#}</dt>
                                    <dd class="account-time">
                                        {#Account_Management_Game_Time_Unlimited#}
                                    </dd>
                                    <dt class="subcategory">{#Account_Management_Game_Category#}</dt>
                                        <dd class="account primary-account account-status">
                                            <span class="account-history">{$Account.expansion_name}
                                            <em>{#Account_Management_Digital_Edition#}</em>
                                            </span>
                                        </dd>
                                        {foreach $Account.previous_expansions as $PEx}
                                            <dd class="account secondary-account" style="display: none;">{$PEx}
                                                <em>{#Account_Management_Digital_Edition#}</em>
                                            </dd>
                                        {/foreach}
                                    <dt class="subcategory">{#Account_Management_Game_Region#}</dt>
                                    <dd class="account-region EU">{#Account_Management_WoW_Europe#}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="secondary">
                    <div class="service-selection character-services">
                        <ul class="wow-services">
                            <li class="category"><a href="#character-services" class="character-services">{#Account_Management_Service_CS#}</a></li>
                            <li class="category"><a href="#additional-services" class="additional-services">{#Account_Management_Service_AS#}</a></li>
                            <li class="category"><a href="#referrals-rewards" class="referrals-rewards">{#Account_Management_Service_RR#}</a></li>
                            <li class="category"><a href="#game-time-subscriptions" class="game-time-subscriptions">{#Account_Management_Service_GTS#}</a></li>
                        </ul>
                        <div class="service-links">
                            <div class="position" style="left: 91px;"></div>
                            <div class="content character-services" id="character-services">
                                <ul>
                                    <li class="wow-service pct">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PCT" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PCT#}</strong>
                                            {#Account_Management_Service_PCT_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service pfc">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PFC#}</strong>
                                            {#Account_Management_Service_PFC_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service prc">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PRC" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PRC#}</strong>
                                            {#Account_Management_Service_PRC_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service pcc">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PCC" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PCC#}</strong>
                                            {#Account_Management_Service_PCC_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service pnc">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PNC" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PNC#}</strong>
                                            {#Account_Management_Service_PNC_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service fir">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=FIR" onclick="">
                                            <span class="icon glow-shadow-3" style='background: url("/Templates/{$Template}/images/dashboard/wow/buttons/item_restoration.png") no-repeat left top; background-size: 31px 31px; background-color: #2d2f2c;'></span>
                                            <strong>{#Account_Management_Service_FIR#}</strong>
                                            {#Account_Management_Service_FIR_Description#}
                                        </a>
                                    </li>
                                    <li class="wow-service wow_mag">
                                        <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PCB" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_PCB#}</strong>
                                            {#Account_Management_Service_PCB_Description#}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="content additional-services" id="additional-services">
                                <ul>

                                </ul>
                            </div>
                            <div class="content referrals-rewards" id="referrals-rewards">
                                <ul>
                                    <li class="wow-service raf">
                                        <a href="/account/management/services/referrals?accountName=WoW{$Account.id}&amp;service=RAF&amp;servicecat=description" onclick="">
                                            <span class="icon glow-shadow-3"></span>
                                            <strong>{#Account_Management_Service_RAF#}</strong>
                                            {#Account_Management_Service_RAF_Description#}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="content game-time-subscriptions" id="game-time-subscriptions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-rating-wrapper">
                    <div class="product-rating mkrf-rating clearfix">
                        <a href="http://mkrf.ru/" tabindex="1" rel="external" target="_blank"><img class="mkrf-logo" src="/Templates/{$Template}/images/legal/ratings/mkrf/12.png" alt="12+" width="65" height="72" /></a>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    var inputs = new Inputs('#game-time, #limited-game-time-purchase');
                    $('#game-time [checked]').parents('label').addClass('selected');
                });
                //]]>
            </script>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}