<script type="text/javascript">
    //<![CDATA[
    var MsgSummary = {
        inventory: {
            slots: {
                1: "Head",
                2: "Neck",
                3: "Shoulder",
                4: "Shirt",
                5: "Chest",
                6: "Waist",
                7: "Legs",
                8: "Feet",
                9: "Wrist",
                10: "Hands",
                11: "Finger",
                12: "Trinket",
                15: "Ranged",
                16: "Back",
                19: "Tabard",
                21: "Main Hand",
                22: "Off Hand",
                28: "Relic",
                empty: "Empty slot"
            }
        },
        audit: {
            whatIsThis: "This feature makes recommendations on how this character can be improved. The following is verified:<br /\><br /\>- Empty glyph slots<br /\>- Unspent talent points<br /\>- Unenchanted items<br /\>- Empty sockets<br /\>- Non-optimal armour<br /\>- Missing belt buckle<br /\>- Unused profession perks",
            missing: "Missing {ldelim}0{rdelim}",
            enchants: {
                tooltip: "Unenchanted"
            },
            sockets: {
                singular: "empty socket",
                plural: "empty sockets"
            },
            armor: {
                tooltip: "Non-{ldelim}0{rdelim}",
                1: "Cloth",
                2: "Leather",
                3: "Mail",
                4: "Plate"
            },
            lowLevel: {
                tooltip: "Low level"
            },
            blacksmithing: {
                name: "Blacksmithing",
                tooltip: "Missing socket"
            },
            enchanting: {
                name: "Enchanting",
                tooltip: "Unenchanted"
            },
            engineering: {
                name: "Engineering",
                tooltip: "Missing tinker"
            },
            inscription: {
                name: "Inscription",
                tooltip: "Missing enchant"
            },
            leatherworking: {
                name: "Leatherworking",
                tooltip: "Missing enchant"
            }
        },
        talents: {
            specTooltip: {
                title: "Talent Specializations",
                primary: "Primary:",
                secondary: "Secondary:",
                active: "Active"
            }
        },
        stats: {
            toggle: {
                all: "Show all stats",
                core: "Show core stats only"
            },
            increases: {
                attackPower: "Increases Attack Power by {ldelim}0{rdelim}.",
                critChance: "Increases Crit chance by {ldelim}0{rdelim}%.",
                spellCritChance: "Increases Spell Crit chance by {ldelim}0{rdelim}%.",
                spellPower: "Increases Spell Power by {ldelim}0{rdelim}.",
                health: "Increases Health by {ldelim}0{rdelim}.",
                mana: "Increases Mana by {ldelim}0{rdelim}.",
                manaRegen: "Increases mana regeneration while in combat by {ldelim}0{rdelim} per 5 Seconds.",
                meleeDps: "Increases damage with melee weapons by {ldelim}0{rdelim} damage per second.",
                rangedDps: "Increases damage with ranged weapons by {ldelim}0{rdelim} damage per second.",
                petArmor: "Increases your pet’s Armour by {ldelim}0{rdelim}.",
                petAttackPower: "Increases your pet’s Attack Power by {ldelim}0{rdelim}.",
                petSpellDamage: "Increases your pet’s Spell Damage by {ldelim}0{rdelim}.",
                petAttackPowerSpellDamage: "Increases your pet’s Attack Power by {ldelim}0{rdelim} and Spell Damage by {ldelim}1{rdelim}."
            },
            decreases: {
                damageTaken: "Reduces Physical Damage taken by {ldelim}0{rdelim}%.",
                enemyRes: "Reduces enemy resistances by {ldelim}0{rdelim}.",
                dodgeParry: "Reduces chance to be dodged or parried by {ldelim}0{rdelim}%."
            },
            noBenefits: "Provides no benefit for your class.",
            beforeReturns: "(Before diminishing returns)",
            damage: {
                speed: "Attack speed (seconds):",
                damage: "Damage:",
                dps: "Damage per second:"
            },
            averageItemLevel: {
                title: "Item Level {ldelim}0{rdelim}",
                description: "The average item level of your best equipment. Increasing this will allow you to enter more difficult dungeons using Dungeon Finder."
            },
            health: {
                title: "Health {ldelim}0{rdelim}",
                description: "Your maximum amount of health. If your health reaches zero, you will die."
            },
            mana: {
                title: "Mana {ldelim}0{rdelim}",
                description: "Your maximum mana. Mana allows you to cast spells."
            },
            rage: {
                title: "Rage {ldelim}0{rdelim}",
                description: "Your maximum rage. Rage is consumed when using abilities and is restored by attacking enemies or being damaged in combat."
            },
            focus: {
                title: "Focus {ldelim}0{rdelim}",
                description: "Your maximum focus. Focus is consumed when using abilities and is restored automatically over time."
            },
            energy: {
                title: "Energy {ldelim}0{rdelim}",
                description: "Your maximum energy. Energy is consumed when using abilities and is restored automatically over time."
            },
            runic: {
                title: "Runic {ldelim}0{rdelim}",
                description: "Your maximum Runic Power."
            },
            strength: {
                title: "Strength {ldelim}0{rdelim}"
            },
            agility: {
                title: "Agility {ldelim}0{rdelim}"
            },
            stamina: {
                title: "Stamina {ldelim}0{rdelim}"
            },
            intellect: {
                title: "Intellect {ldelim}0{rdelim}"
            },
            spirit: {
                title: "Spirit {ldelim}0{rdelim}"
            },
            mastery: {
                title: "Mastery {ldelim}0{rdelim}",
                description: "Mastery {ldelim}0{rdelim} (+{ldelim}1{rdelim}% mastery)",
                unknown: "You must learn Mastery from your trainer before this will have an effect.",
                unspecced: "You must select a talent specialization in order to activate Mastery."
            },
            crit: {
                title: "Critical Strike {ldelim}0{rdelim}%",
                description: "Chance for extra effectiveness on attack and heals.",
                description2: "Critical Strike: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            haste: {
                title: "Haste +{ldelim}0{rdelim}%",
                description: "Increases attack speed and spell casting speed.",
                description2: "Haste: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            meleeDps: {
                title: "Damage per Second"
            },
            meleeAttackPower: {
                title: "Melee Attack Power {ldelim}0{rdelim}"
            },
            meleeSpeed: {
                title: "Melee Attack Speed {ldelim}0{rdelim}"
            },
            meleeHaste: {
                title: "Melee Haste {ldelim}0{rdelim}%",
                description: "Haste rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Haste.",
                description2: "Increases melee attack speed."
            },
            meleeHit: {
                title: "Melee Hit Chance {ldelim}0{rdelim}%",
                description: "Hit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Hit chance."
            },
            meleeCrit: {
                title: "Melee Crit Chance {ldelim}0{rdelim}%",
                description: "Crit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Crit chance.",
                description2: "Chance of melee attacks doing extra damage."
            },
            expertise: {
                title: "Expertise {ldelim}0{rdelim}",
                description: "Expertise rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim} Expertise."
            },
            rangedDps: {
                title: "Damage per Second"
            },
            rangedAttackPower: {
                title: "Ranged Attack Power {ldelim}0{rdelim}"
            },
            rangedSpeed: {
                title: "Ranged Attack Speed {ldelim}0{rdelim}"
            },
            rangedHaste: {
                title: "Ranged Haste {ldelim}0{rdelim}%",
                description: "Haste rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Haste.",
                description2: "Increases ranged attack speed."
            },
            rangedHit: {
                title: "Ranged Hit Chance {ldelim}0{rdelim}%",
                description: "Hit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Hit chance."
            },
            rangedCrit: {
                title: "Ranged Crit Chance {ldelim}0{rdelim}%",
                description: "Crit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Crit chance.",
                description2: "Chance of ranged attacks doing extra damage."
            },
            spellPower: {
                title: "Spell Power {ldelim}0{rdelim}",
                description: "Increases the damage and healing of spells."
            },
            spellHaste: {
                title: "Spell Haste {ldelim}0{rdelim}%",
                description: "Haste rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Haste.",
                description2: "Increases spell casting speed."
            },
            spellHit: {
                title: "Spell Hit Chance {ldelim}0{rdelim}%",
                description: "Hit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Hit chance."
            },
            spellCrit: {
                title: "Spell Crit Chance {ldelim}0{rdelim}%",
                description: "Crit rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Crit chance.",
                description2: "Chance of spells doing extra damage or healing."
            },
            manaRegen: {
                title: "Mana Regen",
                description: "{ldelim}0{rdelim} mana regenerated every 5 seconds while not in combat."
            },
            combatRegen: {
                title: "Combat Regen",
                description: "{ldelim}0{rdelim} mana regenerated every 5 seconds while in combat."
            },
            armor: {
                title: "Armour {ldelim}0{rdelim}"
            },
            dodge: {
                title: "Dodge Chance {ldelim}0{rdelim}%",
                description: "Dodge rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Dodge chance."
            },
            parry: {
                title: "Parry Chance {ldelim}0{rdelim}%",
                description: "Parry rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Parry chance."
            },
            block: {
                title: "Block Chance {ldelim}0{rdelim}%",
                description: "Block rating of {ldelim}0{rdelim} adds {ldelim}1{rdelim}% Block chance.",
                description2: "Your block stops {ldelim}0{rdelim}% of incoming damage."
            },
            resilience: {
                title: "PvP Resilience {ldelim}0{rdelim}%",
                description: "Provides damage reduction against all damage done by players and their pets or minions.",
                description2: "Resilience {ldelim}0{rdelim} (+{ldelim}1{rdelim}% Resilience)"
            },
            pvppower: {
                title: "PvP Power {ldelim}0{rdelim}%",
                description: "Increases damage done to players and their pets or minions, and increases healing done in PvP zones and outdoors.",
                description2: "Power {ldelim}0{rdelim}",
                description3: "+{ldelim}0{rdelim}% Healing",
                description4: "+{ldelim}0{rdelim}% Damage"
            },
            arcaneRes: {
                title: "Arcane Resistance {ldelim}0{rdelim}",
                description: "Reduces Arcane damage taken by an average of {ldelim}0{rdelim}%."
            },
            fireRes: {
                title: "Fire Resistance {ldelim}0{rdelim}",
                description: "Reduces Fire damage taken by an average of {ldelim}0{rdelim}%."
            },
            frostRes: {
                title: "Frost Resistance {ldelim}0{rdelim}",
                description: "Reduces Frost damage taken by an average of {ldelim}0{rdelim}%."
            },
            natureRes: {
                title: "Nature Resistance {ldelim}0{rdelim}",
                description: "Reduces Nature damage taken by an average of {ldelim}0{rdelim}%."
            },
            shadowRes: {
                title: "Shadow Resistance {ldelim}0{rdelim}",
                description: "Reduces Shadow damage taken by an average of {ldelim}0{rdelim}%."
            },
            bonusArmor: {
                title: "Bonus Armor {ldelim}0{rdelim}",
                description: "Total Armor Physical Damage Reduction: {ldelim}0{rdelim}%",
                description2: "Increases Attack Power by {ldelim}0{rdelim}."
            },
            multistrike: {
                title: "Multistrike {ldelim}0{rdelim}%",
                description: "Grants two {ldelim}0{rdelim}% chances to deliver extra attacks or heals for {ldelim}1{rdelim}% of normal value.",
                description2: "Multistrike: {ldelim}0{rdelim} [{ldelim}1{rdelim}%]"
            },
            leech: {
                title: "Leech {ldelim}0{rdelim}%",
                description: "Returns a portion of your damage and healing as healing to you.",
                description2: "Leech: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            },
            versatility: {
                title: "Versatility {ldelim}0{rdelim}%/{ldelim}1{rdelim}%",
                description: "Increases damage and healing done by {ldelim}0{rdelim}% and decreases damage taken by {ldelim}1{rdelim}%.",
                description2: "Versatility: {ldelim}0{rdelim} [{ldelim}1{rdelim}%/{ldelim}2{rdelim}%]"
            },
            avoidance: {
                title: "Avoidance {ldelim}0{rdelim}%",
                description: "Reduces damage taken from area of effect attacks.",
                description2: "Avoidance: {ldelim}0{rdelim} [+{ldelim}1{rdelim}%]"
            }
        },
        recentActivity: {
            subscribe: "Subscribe to this feed"
        },
        raid: {
            tooltip: {
                lfr: "(LFR)",
                flex: "(Flexible)",
                normal: "(Normal)",
                heroic: "(Heroic)",
                mythic: "(Mythic)",
                complete: "{ldelim}0{rdelim}% complete ({ldelim}1{rdelim}/{ldelim}2{rdelim})",
                optional: "(optional)"
            }
        },
        links: {
            tools: "Tools",
            saveImage: "Export character image",
            saveimageTitle: "Export your character image for use with the World of Warcraft Rewards Visa credit card."
        }
    };
    //]]>
</script>
