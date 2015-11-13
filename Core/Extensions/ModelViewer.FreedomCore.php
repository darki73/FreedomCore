<?php

Class ModelViewer
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;
    protected static $EquippedItems = array();
    protected static $CharacterRace;
    protected static $CharacterGender;
    private static $ObjectWidth;
    private static $ObjectHeight;


    public function __construct($VariablesArray)
    {
        ModelViewer::$DBConnection = $VariablesArray[0]::$Connection;
        ModelViewer::$CharConnection = $VariablesArray[0]::$CConnection;
        ModelViewer::$WConnection = $VariablesArray[0]::$WConnection;
        ModelViewer::$TM = $VariablesArray[1];
    }

    public static function Initialize($Height, $Width)
    {
        ModelViewer::$ObjectWidth = $Width;
        ModelViewer::$ObjectHeight = $Height;
    }

    public static function SetCharacterData($Race, $Gender)
    {
        ModelViewer::$CharacterRace = $Race;
        if($Gender == 0)
            ModelViewer::$CharacterGender = 'male';
        else
            ModelViewer::$CharacterGender = 'female';
    }

    public static function EquipItem($SlotID, $DisplayID)
    {
        $IgnoreSlots = array(1, 10, 11, 12, 13);
        if(!in_array($SlotID, $IgnoreSlots))
        {
            if($SlotID <= 9)
            {
                $UpdatedSlotID = $SlotID + 1;
                ModelViewer::$EquippedItems[$UpdatedSlotID] = $DisplayID;
            }
            elseif($SlotID == 14)
                ModelViewer::$EquippedItems[16] = $DisplayID;
            elseif($SlotID == 15)
                ModelViewer::$EquippedItems[21] = $DisplayID;
            elseif($SlotID == 16)
                ModelViewer::$EquippedItems[17] = $DisplayID;
            elseif($SlotID == 18)
                ModelViewer::$EquippedItems[19] = $DisplayID;
        }
    }

    public static function UnequipAll()
    {
        unset(ModelViewer::$EquippedItems);
        ModelViewer::$EquippedItems = array();
    }

    public static function IsSlotEquipped($SlotId)
    {
        return isset(ModelViewer::$EquippedItems[$SlotId]);
    }

    public static function GetEquippedItem($SlotId)
    {
        if( !ModelViewer::IsSlotEquipped($SlotId))
            return -1;
        else
            return ModelViewer::$EquippedItems[$SlotId];
    }

    public static function GetCharacterHtml()
    {
        $HtmlFormat = '
                <object type="application/x-shockwave-flash" data="//wow.zamimg.com/modelviewer/ZAMviewerfp11.swf" width="%u" height="%u" id="paperdoll-model-paperdoll-0-equipment-set">
                    <param name="quality" value="high">
                    <param name="wmode" value="direct"/>
                    <param name="allowsscriptaccess" value="always">
                    <param name="menu" value="false">
                    <param name="flashvars"  value="hd=false&amp;model=%s&amp;modelType=16&amp;contentPath=//wow.zamimg.com/modelviewer/&amp;%s">
                </object>
        ';

        $ModelType = ModelViewer::$CharacterRace.ModelViewer::$CharacterGender;
        $EquipList = "";
        if(sizeof(ModelViewer::$EquippedItems)>0)
        {
            $EquipList = "equipList=";

            foreach(ModelViewer::$EquippedItems AS $Slot => $DisplayId )
                $EquipList .= "$Slot,$DisplayId,";

            $EquipList = rtrim( $EquipList, "," );
        }

        return sprintf($HtmlFormat, ModelViewer::$ObjectWidth, ModelViewer::$ObjectHeight, $ModelType, $EquipList);
    }
}

?>