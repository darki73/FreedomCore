<?php

Class Menu
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Menu::$DBConnection = $VariablesArray[0]::$Connection;
        Menu::$CharConnection = $VariablesArray[0]::$CConnection;
        Menu::$WConnection = $VariablesArray[0]::$WConnection;
        Menu::$TM = $VariablesArray[1];
    }

    public static function GenerateMenu()
    {
        global $FCCore;
        $JSONMenu = array(
            "label" => $FCCore['ApplicationName'],
            "url" => '/',
            "children" => array(
                array(
                    "label" => Menu::$TM->GetConfigVars('Menu_Game'),
                    "url" => "/game/",
                    "children" => array(
                        array(
                            "label" => Menu::$TM->GetConfigVars('Game_FAQ_Title'),
                            "url" => "/game/guide/",
                            "children" => array(
                                array()
                            )
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Game_WelcomeBack_Title'),
                            "url" => "/game/returning-players-guide/"
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Notes'),
                            "url" => "/game/patch-notes/",
                            "children" => array(
                                array(
                                    "label" => "Warlords of Draenor",
                                    "url" => "/game/patch-notes/6-0",
                                    "children" => array(
                                        array(
                                            "label" => "Warlords of Draenor",
                                            "url" => "/game/patch-notes/6-0"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_SoO'),
                                    "url" => "/game/patch-notes/5-4",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_SoO'),
                                            "url" => "/game/patch-notes/5-4"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Rising'),
                                    "url" => "/game/patch-notes/5-3",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Rising'),
                                            "url" => "/game/patch-notes/5-3"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_ThunderLord'),
                                    "url" => "/game/patch-notes/5-2",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_ThunderLord'),
                                            "url" => "/game/patch-notes/5-2"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Landing'),
                                    "url" => "/game/patch-notes/5-1",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Landing'),
                                            "url" => "/game/patch-notes/5-1"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_MoPPreparation'),
                                    "url" => "/game/patch-notes/5-0",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_MoPPreparation'),
                                            "url" => "/game/patch-notes/5-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 5.0.4",
                                            "url" => "/game/patch-notes/5-0-4"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')."5.0.5",
                                            "url" => "/game/patch-notes/5-0-5"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Twilight'),
                                    "url" => "/game/patch-notes/4-3",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.3.0",
                                            "url" => "/game/patch-notes/4-3-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.3.2",
                                            "url" => "/game/patch-notes/4-3-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.3.3",
                                            "url" => "/game/patch-notes/4-3-3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.3.4",
                                            "url" => "/game/patch-notes/4-3-4"
                                        ),
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Firelands'),
                                    "url" => "/game/patch-notes/4-2",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.2.0",
                                            "url" => "/game/patch-notes/4-2-0"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Zandalari'),
                                    "url" => "/game/patch-notes/4-1",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.1.0",
                                            "url" => "/game/patch-notes/4-1-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.1.0a",
                                            "url" => "/game/patch-notes/4-1-0a"
                                        ),
                                    )
                                ),
                                array(
                                    "label" => "Cataclysm",
                                    "url" => "/game/patch-notes/4-0",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.0.1",
                                            "url" => "/game/patch-notes/4-0-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.0.3",
                                            "url" => "/game/patch-notes/4-0-3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Patch_Version')." 4.0.6",
                                            "url" => "/game/patch-notes/4-0-6"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK'),
                                    "url" => "/game/patch-notes/3-3",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.3.0",
                                            "url" => "/game/patch-notes/3-3-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.3.0a",
                                            "url" => "/game/patch-notes/3-3-0a"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.3.2",
                                            "url" => "/game/patch-notes/3-3-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.3.3",
                                            "url" => "/game/patch-notes/3-3-3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_FotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.3.5",
                                            "url" => "/game/patch-notes/3-3-5"
                                        ),
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Avangard'),
                                    "url" => "/game/patch-notes/3-2",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Avangard').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.2.0",
                                            "url" => "/game/patch-notes/3-2-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Avangard').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.2.2",
                                            "url" => "/game/patch-notes/3-2-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Avangard').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.2.2a",
                                            "url" => "/game/patch-notes/3-2-2a"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Ulduar'),
                                    "url" => "/game/patch-notes/3-1",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Ulduar').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.1.0",
                                            "url" => "/game/patch-notes/3-1-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Ulduar').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.1.1",
                                            "url" => "/game/patch-notes/3-1-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Ulduar').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.1.2",
                                            "url" => "/game/patch-notes/3-1-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Ulduar').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.1.3",
                                            "url" => "/game/patch-notes/3-1-3"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_WotLK'),
                                    "url" => "/game/patch-notes/3-0",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_WotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.0.2",
                                            "url" => "/game/patch-notes/3-0-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_WotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.0.3",
                                            "url" => "/game/patch-notes/3-0-3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_WotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.0.8",
                                            "url" => "/game/patch-notes/3-0-8"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_WotLK').": ".Menu::$TM->GetConfigVars('Patch_Version')." 3.0.9",
                                            "url" => "/game/patch-notes/3-0-9"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Sunwell'),
                                    "url" => "/game/patch-notes/2-4",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Sunwell').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.4.0",
                                            "url" => "/game/patch-notes/2-4-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Sunwell').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.4.1",
                                            "url" => "/game/patch-notes/2-4-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Sunwell').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.4.2",
                                            "url" => "/game/patch-notes/2-4-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Sunwell').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.4.3",
                                            "url" => "/game/patch-notes/2-4-3"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Zulaman'),
                                    "url" => "/game/patch-notes/2-3",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Zulaman').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.3.0",
                                            "url" => "/game/patch-notes/2-3-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Zulaman').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.3.2",
                                            "url" => "/game/patch-notes/2-3-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Zulaman').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.3.3",
                                            "url" => "/game/patch-notes/2-3-3"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_BlackTemple'),
                                    "url" => "/game/patch-notes/2-1",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BlackTemple').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.1.0",
                                            "url" => "/game/patch-notes/2-1-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BlackTemple').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.1.1",
                                            "url" => "/game/patch-notes/2-1-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BlackTemple').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.1.2",
                                            "url" => "/game/patch-notes/2-1-2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BlackTemple').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.1.3",
                                            "url" => "/game/patch-notes/2-1-3"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade'),
                                    "url" => "/game/patch-notes/2-0",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.1",
                                            "url" => "/game/patch-notes/2-0-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.3",
                                            "url" => "/game/patch-notes/2-0-3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.4",
                                            "url" => "/game/patch-notes/2-0-4"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.5",
                                            "url" => "/game/patch-notes/2-0-5"
                                        ),array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.6",
                                            "url" => "/game/patch-notes/2-0-6"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.7",
                                            "url" => "/game/patch-notes/2-0-7"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.8",
                                            "url" => "/game/patch-notes/2-0-8"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.10",
                                            "url" => "/game/patch-notes/2-0-10"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_BurningCrusade').": ".Menu::$TM->GetConfigVars('Patch_Version')." 2.0.12",
                                            "url" => "/game/patch-notes/2-0-12"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Game_Patch_Necropolis'),
                                    "url" => "/game/patch-notes/1-11",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Necropolis').": ".Menu::$TM->GetConfigVars('Patch_Version')." 1.11.0",
                                            "url" => "/game/patch-notes/1-11-0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Necropolis').": ".Menu::$TM->GetConfigVars('Patch_Version')." 1.11.1",
                                            "url" => "/game/patch-notes/1-11-1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Game_Patch_Necropolis').": ".Menu::$TM->GetConfigVars('Patch_Version')." 1.11.2",
                                            "url" => "/game/patch-notes/1-11-2"
                                        )
                                    )
                                )
                            )
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Item_Category'),
                            "url" => "/item",
                            "children" => array(
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Weapon'),
                                    "url" => "/item/?classId=2",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Axe_1H'),
                                            "url" => "/item/?classId=2&subClassId=0"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Axe_2H'),
                                            "url" => "/item/?classId=2&subClassId=1"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Bow'),
                                            "url" => "/item/?classId=2&subClassId=2"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Gun'),
                                            "url" => "/item/?classId=2&subClassId=3"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Mace_1H'),
                                            "url" => "/item/?classId=2&subClassId=4"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Mace_2H'),
                                            "url" => "/item/?classId=2&subClassId=5"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Polearm'),
                                            "url" => "/item/?classId=2&subClassId=6"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Sword_1H'),
                                            "url" => "/item/?classId=2&subClassId=7"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Sword_2H'),
                                            "url" => "/item/?classId=2&subClassId=8"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Staff'),
                                            "url" => "/item/?classId=2&subClassId=10"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Fist_Weapon'),
                                            "url" => "/item/?classId=2&subClassId=13"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Miscellaneous'),
                                            "url" => "/item/?classId=2&subClassId=14"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Dagger'),
                                            "url" => "/item/?classId=2&subClassId=15"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Thrown'),
                                            "url" => "/item/?classId=2&subClassId=16"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Crossbow'),
                                            "url" => "/item/?classId=2&subClassId=18"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Wand'),
                                            "url" => "/item/?classId=2&subClassId=19"
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Fishing_Pole'),
                                            "url" => "/item/?classId=2&subClassId=20"
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Armor'),
                                    "url" => "/item/?classId=4",
                                    "children" => array(
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_Class_Miscellaneous'),
                                            "url" => "/item/?classId=4&subClassId=0",
                                            "children" => array(
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Head'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=1"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Neck'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=2"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Shirt'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=4"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Finger'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=11"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Trinket'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=12"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Off_Hand'),
                                                    "url" => "/item/?classId=4&subClassId=0&invType=23"
                                                )
                                            )
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Cloth'),
                                            "url" => "/item/?classId=4&subClassId=1",
                                            "children" => array(
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Head'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=1"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Shoulder'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=3"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Chest'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=5"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Waist'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=6"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Legs'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=7"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Feet'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=8"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Wrists'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=9"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Hands'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=10"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Back'),
                                                    "url" => "/item/?classId=4&subClassId=1&invType=16"
                                                )
                                            )
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Leather'),
                                            "url" => "/item/?classId=4&subClassId=2",
                                            "children" => array(
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Head'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=1"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Shoulder'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=3"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Chest'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=5"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Waist'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=6"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Legs'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=7"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Feet'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=8"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Wrists'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=9"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Hands'),
                                                    "url" => "/item/?classId=4&subClassId=2&invType=10"
                                                )
                                            )
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Mail'),
                                            "url" => "/item/?classId=4&subClassId=3",
                                            "children" => array(
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Head'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=1"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Shoulder'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=3"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Chest'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=5"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Waist'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=6"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Legs'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=7"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Feet'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=8"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Wrists'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=9"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Hands'),
                                                    "url" => "/item/?classId=4&subClassId=3&invType=10"
                                                )
                                            )
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Plate'),
                                            "url" => "/item/?classId=4&subClassId=4",
                                            "children" => array(
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Head'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=1"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Shoulder'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=3"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Chest'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=5"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Waist'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=6"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Legs'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=7"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Feet'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=8"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Wrists'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=9"
                                                ),
                                                array(
                                                    "label" => Menu::$TM->GetConfigVars('Item_InventoryType_Hands'),
                                                    "url" => "/item/?classId=4&subClassId=4&invType=10"
                                                )
                                            )
                                        ),
                                        array(
                                            "label" => Menu::$TM->GetConfigVars('Item_SubClass_Shield'),
                                            "url" => "/item/?classId=4&subClassId=6",
                                        )
                                    )
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Bags'),
                                    "url" => "/item/?classId=1",
                                    "children" =>Menu::GetSubCategories(1)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Consumable'),
                                    "url" => "/item/?classId=0",
                                    "children" => Menu::GetSubCategories(0)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Glyph'),
                                    "url" => "/item/?classId=16",
                                    "children" => Menu::GetSubCategories(16)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Trade_Goods'),
                                    "url" => "/item/?classId=7",
                                    "children" => Menu::GetSubCategories(7)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Recipe'),
                                    "url" => "/item/?classId=9",
                                    "children" => Menu::GetSubCategories(9)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Gem'),
                                    "url" => "/item/?classId=3",
                                    "children" => Menu::GetSubCategories(3)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Miscellaneous'),
                                    "url" => "/item/?classId=15",
                                    "children" => Menu::GetSubCategories(15)
                                ),
                                array(
                                    "label" => Menu::$TM->GetConfigVars('Item_Class_Quest'),
                                    "url" => "/item/?classId=12"
                                ),
                            )
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Menu_Player_Chars'),
                            "parentClass" => "divider",
                            "url" =>  ""
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Game_Races'),
                            "url" => "/game/race/",
                            "children" => Menu::GetRaces()
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Game_Classes'),
                            "url" => "/game/class/",
                            "children" => Menu::GetClasses()
                        ),
                        array(
                            "label" => Menu::$TM->GetConfigVars('Profile_Character_Professions'),
                            "url" => "/game/profession/",
                            "children" => Menu::GetProfessions()
                        )
                    )
                )
            )
        );
        $ConvertToJSON = json_encode($JSONMenu, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $ConvertToJSON;
    }

    private static function GetSubCategories($SubCategoryID)
    {
        $Categories = array();
        if($SubCategoryID == 16)
        {
            foreach(Items::ItemSubClass(16, 0, true) as $MenuItem)
            {
                $Categories[] = array(
                    "label" => $MenuItem['translation'],
                    "parentClass" => "color-c".$MenuItem['subclass'],
                    "url" => "/item/?classId=16&subClassId=".$MenuItem['subclass']
                );
            }
        }
        else
        {
            foreach(Items::ItemSubClass($SubCategoryID, 0, true) as $Key=>$MenuItem)
            {
                if($SubCategoryID == 7 && $Key == 0)
                    continue;
                else
                    $Categories[] = array(
                        "label" => $MenuItem['translation'],
                        "url" => "/item/?classId=".$SubCategoryID."&subClassId=".$MenuItem['subclass']
                    );
            }
        }
        return $Categories;
    }

    private static function RaceBySide($SideID)
    {
        $Races = array();
        $HordeRaces = array(2, 5, 6, 8, 9, 10, 26);
        $AllianceRaces = array(1, 3, 4, 7, 11, 22, 25);
        if($SideID == 1)
        {
            foreach($HordeRaces as $Horde)
            {
                $GetRaceInfo = Characters::GetRaceByID($Horde);
                $Races[] = array(
                    "label" => $GetRaceInfo['translation'],
                    "url" => "/game/race/".$GetRaceInfo['name']
                );
            }
        }
        else
        {
            foreach($AllianceRaces as $Alliance)
            {
                $GetRaceInfo = Characters::GetRaceByID($Alliance);
                $Races[] = array(
                    "label" => $GetRaceInfo['translation'],
                    "url" => "/game/race/".$GetRaceInfo['name']
                );
            }
        }
        return $Races;
    }

    private static function GetClasses()
    {
        $Classes = array();
        foreach(Characters::GetClassByID(0, true) as $Key=>$Value)
        {
            $Classes[] = array(
                "label" => $Value['translation'],
                "parentClass" => "color-c".$Key,
                "url" => "/game/class/".$Value['name']
            );
        }
        return $Classes;
    }

    private static function GetRaces()
    {
        $HordeRaces[] = array(
            "label" => Menu::$TM->GetConfigVars('Horde'),
            "parentClass" => "divider",
            "url" => ""
        );
        $AllianceRaces[] = array(
            "label" => Menu::$TM->GetConfigVars('Alliance'),
            "parentClass" => "divider",
            "url" => ""
        );
        $HordeRaces = array_merge($HordeRaces, Menu::RaceBySide(1));
        $AllianceRaces = array_merge($AllianceRaces, Menu::RaceBySide(0));
        return array_merge($AllianceRaces, $HordeRaces);
    }

    private static function GetProfessions()
    {
        $Professions = array();
        $Statement = Menu::$DBConnection->prepare('SELECT * FROM professions');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($Result as $Profession)
        {
            $Professions[] = array(
                "label" => Menu::$TM->GetConfigVars($Profession['profession_translation']),
                "url" => "/game/profession/".$Profession['profession_name']
            );
        }
        return $Professions;
    }
}