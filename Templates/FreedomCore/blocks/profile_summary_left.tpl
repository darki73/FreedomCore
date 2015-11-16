<div class="summary-bottom-left">
    <div class="summary-health-resource">
        <ul>
            <li class="health" id="summary-health" data-id="health">
                <span class="name">Здоровье</span>
                <span class="value">
                    {$Character.health}
                </span>
            </li>
            <li class="resource-{$Character.power_data.id}" id="summary-power" data-id="power-{$Character.power_data.id}">
                <span class="name">{$Character.power_data.translation}</span>
                <span class="value">
                        {$Character.power_data.value}
                </span>
            </li>
        </ul>
    </div>
    <div class="summary-stats-profs-bgs">

        <div class="summary-stats" id="summary-stats">
            <div id="summary-stats-simple" class="summary-stats-simple" style=" display: block">
                <div class="summary-stats-simple-base">
                    <div class="summary-stats-column">
                        <h4>{#Character_Stat_Main#}</h4>
                        <ul>
                            <li data-id="strength" class="">
                                <span class="name">{#Character_Stat_Strength#}</span>
                                <span class="value">{$Inventory.StrengthValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="agility" class="">
                                <span class="name">{#Character_Stat_Agility#}</span>
                                <span class="value">{$Inventory.AgilityValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="intellect" class="">
                                <span class="name">{#Character_Stat_Intellect#}</span>
                                <span class="value">{$Inventory.IntellectValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="spirit" class="">
                                <span class="name">{#Character_Stat_Spirit#}</span>
                                <span class="value">{$Inventory.SpiritValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="stamina" class="">
                                <span class="name">{#Character_Stat_Stamina#}</span>
                                <span class="value">{$Inventory.StaminaValue}</span>
                                {*color-q2 if we want to highlight stats for particular class [WIP]*}
                                <span class="clear"><!-- --></span>
                            </li>
                        </ul>
                    </div>


                    <div class="summary-stats-column">
                        <h4>Атака</h4>
                        <ul>
                            <li data-id="attackdamage" class="">
                                <span class="name">{#Item_Damage#}</span>
                                <span class="value">
                                    {if $Inventory.MainHandSpeed != 0}
                                        {$Inventory.MainHandDamage.minimum} - {$Inventory.MainHandDamage.maximum}
                                    {/if}
                                </span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="attackpower" class="">
                                <span class="name">AP</span>
                                <span class="value">{$Inventory.AttackPower}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="attackspeed" class="">
                                {if $Inventory.MainHandSpeed != 0}
                                    {$HasteRatingMH = ($Inventory.MainHandSpeed/1000) / (($Inventory.HasteValue/$Character.level_data.hastepoints)/100 +1)}
                                {/if}
                                {if $Inventory.OffHandSpeed != 0}
                                    {$HasteRatingOH = ($Inventory.OffHandSpeed/1000) / (($Inventory.HasteValue/$Character.level_data.hastepoints)/100 +1)}
                                {/if}
                                {if $Inventory.RangedSpeed != 0}
                                    {$HasteRatingR = ($Inventory.RangedSpeed/1000) / (($Inventory.HasteValue/$Character.level_data.hastepoints)/100 +1)}
                                {/if}

                                <span class="name">{#MSG_Summary_Stats_damage_speed#}</span>
                                <span class="value">
                                    {if $Inventory.MainHandSpeed != 0}
                                        {$HasteRatingMH|string_format:"%.2f"}
                                    {/if}
                                    {if $Inventory.OffHandSpeed != 0}
                                        / {$HasteRatingOH|string_format:"%.2f"}
                                    {/if}
                                    {if $Inventory.RangedSpeed !=0 && $Inventory.MainHandSpeed <= 2000}
                                        {$HasteRatingR|string_format:"%.2f"}
                                    {/if}
                                </span>
                                <span class="clear"><!-- --></span>
                            </li>
                        </ul>
                    </div>


                    <div class="summary-stats-column">
                        <h4>Заклинания</h4>
                        <ul>
                            <li data-id="spellpower" class="">
                                <span class="name">Сила заклинаний</span>
                                <span class="value">{$Inventory.SpellPowerValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="manaregen" class=" no-tooltip">
                                <span class="name">Восполнение маны</span>
                                <span class="value color-q0">--</span>
                                <span class="clear"><!-- --></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="summary-stats-simple-other">


                    <div class="summary-stats-column">
                        <h4>Защита</h4>
                        <ul>
                            <li data-id="armor" class="">
                                <span class="name">{#Item_Class_Armor#}</span>
                                <span class="value">{$Inventory.ArmorValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="dodge" class="">
                                <span class="name">{#Character_Stat_Dodge#}</span>
                                <span class="value">{$Inventory.DodgeValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="parry" class="">
                                <span class="name">{#Character_Stat_Parry#}</span>
                                <span class="value">{$Inventory.ParryValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="block" class="">
                                <span class="name">{#Character_Stat_Block#}</span>
                                <span class="value">{$Inventory.BlockValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                        </ul>
                    </div>


                    <div class="summary-stats-column">
                        <h4>Усиления</h4>
                        <ul>
                            <li data-id="crit" class="">
                                <span class="name">{#Character_Stat_Crit#}</span>
                                <span class="value">{$Inventory.CritValue}</span>
                                <span class="clear"><!-- --></span>
                            </li>
                            <li data-id="haste" class="">
                                <span class="name">{#Character_Stat_Haste#}</span>
                                <span class="value">{$Inventory.HasteValue}</span>
                                <span class="clear"><!-- --></span>
                            {*</li>*}
                            {*<li data-id="mastery" class="">*}
                                {*<span class="name">Искусность</span>*}
                                {*<span class="value color-q2">19,51%</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                            {*<li data-id="bonusarmor" class="">*}
                                {*<span class="name">Бонус брони</span>*}
                                {*<span class="value">0</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                            {*<li data-id="multistrike" class="">*}
                                {*<span class="name">Многократная атака</span>*}
                                {*<span class="value">11,08%</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                            {*<li data-id="leech" class="">*}
                                {*<span class="name">Cамоисцеление</span>*}
                                {*<span class="value">0,00%</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                            {*<li data-id="versatility" class="">*}
                                {*<span class="name">Универсальность</span>*}
                                {*<span class="value">4,68%</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                            {*<li data-id="avoidance" class="">*}
                                {*<span class="name">Избегание</span>*}
                                {*<span class="value">0,00%</span>*}
                                {*<span class="clear"><!-- --></span>*}
                            {*</li>*}
                        </ul>
                    </div>
                </div>
                <div class="summary-stats-end"></div>
            </div>
        </div>

        {include file="parts/Character_Summary_Stats.tpl"}
        {include file="parts/MSG_Summary.tpl"}

    </div>
</div>