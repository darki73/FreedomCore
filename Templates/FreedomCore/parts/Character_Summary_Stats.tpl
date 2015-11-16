{$CalculateParryRating = $Inventory.ParryValue / $Character.level_data.parrypoints}
{$CalculateDodgeRating = $Inventory.DodgeValue / $Character.level_data.parrypoints}
{$CalculateBlockRating = $Inventory.BlockValue / $Character.level_data.parrypoints}
{$CalculateCritRating = $Inventory.CritValue / $Character.level_data.critpoints}
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        new Summary.Stats({
            // Base Stats
            "health": {$Character.health},
            "power": {$Character.power_data.value},
            "energy": 0,
            "rage": 0,
            "chi": 0,
            "focus": 0,
            "mana": 0,
            "runicPower": 0,
            "powerTypeId": {$Character.power_data.id},
            "hasOffhandWeapon": {if $Inventory.hasOffhand}true{else}false{/if},

            // Item Level Stats
            "averageItemLevelEquipped": {$CountItemLevel},
            "averageItemLevelBest": {$CountItemLevel},

            // Main Hand Stats
            {if $Inventory.MainHandDamage.minimum != -1}
            "dmgMainMin": {$Inventory.MainHandDamage.minimum},
            "dmgMainMax": {$Inventory.MainHandDamage.maximum},
            "dmgMainSpeed": {$HasteRatingMH},
            "dmgMainDps": {$Inventory.MainHandDamage.dps},
            {else}
            "dmgMainMin": -1,
            "dmgMainMax": -1,
            "dmgMainSpeed": -1,
            "dmgMainDps": -1,
            {/if}

            // Off Hand Stats
            {if $Inventory.OffHandDamage.minimum != -1}
            "dmgOffMin": {$Inventory.OffHandDamage.minimum / ($HasteRatingOH / 2)},
            "dmgOffMax": {$Inventory.OffHandDamage.maximum / ($HasteRatingOH / 2)},
            "dmgOffSpeed": {$HasteRatingOH},
            "dmgOffDps": {$Inventory.OffHandDamage.dps / ($HasteRatingOH / 2)},
            {else}
            "dmgOffMin": -1,
            "dmgOffMax": -1,
            "dmgOffSpeed": -1,
            "dmgOffDps": -1,
            {/if}

            // Ranged Stats
            {if $Inventory.RangedDamage.minimum != -1}
            "dmgRangeMax": {$Inventory.RangedDamage.maximum},
            "dmgRangeDps": {$Inventory.RangedDamage.dps},
            "dmgRangeMin": {$Inventory.RangedDamage.minimum},
            "dmgRangeSpeed": {$Inventory.RangedDamage.speed},
            {else}
            "dmgRangeMax": -1,
            "dmgRangeDps": -1,
            "dmgRangeMin": -1,
            "dmgRangeSpeed": -1,
            {/if}

            // Parry Stats
            "parryRating": {$Inventory.ParryValue},
            "parryRatingPercent": {$CalculateParryRating},
            "parry": {$CalculateParryRating},

            // Dodge Stats
            "dodge": {$CalculateDodgeRating},
            "dodgeRating": {$Inventory.DodgeValue},
            "dodgeRatingPercent": {$CalculateDodgeRating},


            // Block Values
            "block": {$CalculateBlockRating},
            "blockRating": {$Inventory.BlockValue},
            "blockRatingPercent": {$CalculateBlockRating},
            "str_block": {$Inventory.StrengthValue / 2},
            "block_damage": 0,


            // Armor Values
            "armorBase": 0, // Need to be revised, havent done it yet
            "armorPercent": {$Inventory.DamageReduction},
            "armorTotal": {$Inventory.ArmorValue},
            "agi_armor": 0,
            "armorPenetration": 0,
            "armorPenetrationPercent": 0,
            "bonusArmor": 0,

            // Crit Values
            {if $Inventory.OffHandSpeed != 0}
            "critPercent": {$CalculateCritRating},
            {else}
            "critPercent": {$CalculateCritRating/1.8},
            {/if}
            "rangeCritRatingPercent": 0,
            "rangeCritPercent": 0,
            "spellCritPercent": 0,
            "critRatingPercent": {$CalculateCritRating},
            "critRating": {$Inventory.CritValue},
            "spellCritRatingPercent": 0,
            "spellCritRating": 0,
            "rangeCritRating": 0,
            "critRatingPercent": 0,

            // Speed Stats
            "speedRatingBonus": 0,
            "speedRating": {$Inventory.HasteValue},
            "pvpPowerDamage": 0,

            // Attack Power Stats
            "atkPowerBase": {$Inventory.AttackPower},
            "atkPowerTotal": {$Inventory.AttackPower},
            "atkPowerLoss": 0,
            "atkPowerBonus": 0,
            "rangeatkPowerBase": 0,
            "rangeatkPowerTotal": 0,
            "rangeatkPowerLoss": 0,
            "rangeatkPowerBonus": 0,
            "str_ap": {$Inventory.AttackPower},
            "agi_ap": 0,

            // Haste Stats
            "spellHaste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "rangedHaste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "spellHasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "haste": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "hasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "rangedHasteRating": 0,
            "rangedHasteRatingPercent": {$Inventory.HasteValue/$Character.level_data.hastepoints},
            "hasteRating": {$Inventory.HasteValue},
            "spellHasteRating": 0,

            // Hit Stats
            "rangeHitPercent": 0,
            "spellHitPercent": 0,
            "spellHitRatingPercent": 0,
            "hitRatingPercent": 0,
            "hitRating": 0,
            "hitPercent": 0,
            "rangeHitRating": 0,
            "spellHitRating": 0,
            "uiHitModifier": 0,
            "rangeHitRatingPercent": 0,
            "uiSpellHitModifier": 0,

            // Main Stats Stats
            "sprTotal": {$Inventory.SpiritValue},
            "sprBase": {$Character.level_data.spi},
            "spr_regen": 0,
            "strBase": {$Character.level_data.str},
            "strTotal": {$Inventory.StrengthValue},
            "intTotal": {$Inventory.IntellectValue},
            "staTotal": {$Inventory.StaminaValue},
            "agiTotal": {$Inventory.AgilityValue},
            "intBase": {$Character.level_data.inte},
            "staBase": {$Character.level_data.sta},
            "sta_hp": {$Character.health},
            "int_sp": 0,
            "agiBase": 0,
            "ap_dps": 0,

            // Bonuses Stats
            "bonusOffWeaponRating": 0,
            "rangeBonusWeaponRating": 0,
            "multistrikeRatingBonus": 0,
            "rangeBonusWeaponSkill": 0,
            "pvpResilienceBonus": 0,
            "masteryRatingBonus": 0,
            "versatilityHealingDoneBonus": 0,
            "lifestealRatingBonus": 0,
            "bonusOffMainWeaponSkill": 0,
            "avoidanceRatingBonus": 0,
            "versatilityDamageDoneBonus": 0,
            "versatilityDamageTakenBonus": 0,
            "sturdinessRatingBonus": 0,
            "bonusMainWeaponSkill": 0,
            "bonusMainWeaponRating": 0,

            // Fire Stats
            "fireResist": 0,
            "fireDamage": 0,
            "fireCrit": 0,

            // Holy Stats
            "holyResist": 0,
            "holyDamage": 0,
            "holyCrit": 0,

            // Frost Stats
            "frostResist": 0,
            "frostDamage": 0,
            "frostCrit": 0,

            // Shadow Stats
            "shadowResist": 0,
            "shadowDamage": 0,
            "shadowCrit": 0,

            // Nature Stats
            "natureResist": 0,
            "natureDamage": 0,
            "natureCrit": 0,

            // Arcane Stats
            "arcaneResist": 0,
            "arcaneDamage": 0,
            "arcaneCrit": 0,

            // PVP Stats
            "pvpResilienceBase": 0,
            "pvpResilience": 0,
            "pvpPowerRating": 0,
            "pvpPowerHealing": 0,
            "pvpPower": 0,
            "pvpResilienceRating": 0,


            // No Idea What is This
            "expertiseOff": 0,
            "spellDamage": 0,
            "expertiseOffPercent": 0,
            "masteryBase": 0,
            "masteryRating": 0,
            "avoidanceRating": 0,
            "manaRegenPerFive": 0,
            "expertiseRatingPercent": 0,
            "multistrikeRating": 0,
            "resilience_damage": 0,
            "expertiseRanged": 0,
            "defensePercent": 0,
            "resilience_crit": 0,
            "healing": 0,
            "manaRegenCombat": 0,
            "spellPenetration": 0,
            "lifesteal": 0,
            "int_crit": 0,
            "lifestealRating": 0,
            "rangedHasteRatingPercent": 0,
            "expertiseMain": 0,
            "defense": 0,
            "mastery": 0,
            "agi_crit": 0,
            "multistrike": 0,
            "versatility": 0,
            "expertiseMainPercent": 0,
            "expertiseRating": 0,
        });
    });
    //]]>
</script>