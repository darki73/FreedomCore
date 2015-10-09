{$CalculateParryRating = $ParryValue / $Character.level_data.parrypoints}
{$CalculateDodgeRating = $DodgeValue / $Character.level_data.parrypoints}
{$CalculateBlockRating = $BlockValue / $Character.level_data.parrypoints}
{$CalculateCritRating = $Inventory.CritValue / $Character.level_data.critpoints}
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        new Summary.Stats({
            "health": {$Character.health},
            "power": {$Character.power_data.value},
            "powerTypeId": {$Character.power_data.id},
            // Item Level Values
            "averageItemLevelEquipped": {$CountItemLevel},
            "averageItemLevelBest": {$CountItemLevel},
            // Parry Values
            "parryRating": {$ParryValue},
            "parryRatingPercent": {$CalculateParryRating},
            "parry": {$CalculateParryRating},
            // Dodge Values
            "dodge": {$CalculateDodgeRating},
            "dodgeRating": {$DodgeValue},
            "dodgeRatingPercent": {$CalculateDodgeRating},
            // Block Values
            "block": {$CalculateBlockRating},
            "blockRating": {$BlockValue},
            "blockRatingPercent": {$CalculateBlockRating},
            "str_block": {$StrengthValue / 2},
            "block_damage": 0,
            // Armor Values
            "armorBase": 0, // Need to be revised, havent done it yet
            "armorPercent": {$Inventory.DamageReduction},
            "armorTotal": {$ArmorValue},
            // Crit Values
            {if $Inventory.OffHandSpeed != 0}
            "critPercent": {$CalculateCritRating},
            {else}
            "critPercent": {$CalculateCritRating/1.8},
            {/if}
            "critRatingPercent": {$CalculateCritRating},
            "critRating": {$Inventory.CritValue},
            // Speed Values
            "speedRatingBonus": 0,
            "speedRating": {$Inventory.HasteValue},
            "dmgMainSpeed": {$HasteRatingMH},
            {if isset($HasteRatingOH)}
            "dmgOffSpeed": {$HasteRatingOH},
            {else}
            "dmgOffSpeed": -1,
            {/if}
            {if isset($HasteRatingR)}
            "dmgRangeSpeed": {$HasteRatingR},
            {else}
            "dmgRangeSpeed": -1,
            {/if}
            // Attack Power Values
            "atkPowerBase": {$Inventory.AttackPower},
            "atkPowerTotal": {$Inventory.AttackPower},
            "atkPowerLoss": 0,
            "atkPowerBonus": 0,
            "str_ap": {$Inventory.AttackPower},
            // Haste Values
            "spellHaste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "rangedHaste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "spellHasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "haste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "hasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "rangedHasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "hasteRating": {$Inventory.HasteValue},
            // Main Stats Values
            "sprTotal": {$SpiritValue},
            "sprBase": {$Character.level_data.spi},
            "spr_regen": 0,
            "strBase": {$Character.level_data.str},
            "strTotal": {$StrengthValue},
            "str_block": {$StrengthValue/2},
            "intTotal": {$IntellectValue},
            "staTotal": {$StaminaValue},
            "intBase": {$Character.level_data.inte},
            "staBase": {$Character.level_data.sta},
            "sta_hp": {$Character.health},
        });
    });
    //]]>
</script>