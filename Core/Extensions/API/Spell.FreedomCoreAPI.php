<?php

Class SpellAPI extends API
{
    public static function GetSimpleSpell($SpellID, $JSONP)
    {
        $SpellInfo = Spells::SpellInfo($SpellID);
        if($SpellInfo)
        {
            $SpellData = String::RemapArray($SpellInfo, ['SpellID', 'Name', 'Description', 'icon'], ['id', 'name', 'description', 'icon']);
            parent::Encode($SpellData, $JSONP);
        }
        else
            parent::GenerateResponse(404, true);
    }
}
?>