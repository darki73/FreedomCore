<?php

Class SpellAPI extends API
{
    public static function GetSimpleSpell($SpellID)
    {
        $SpellData = String::RemapArray(Spells::SpellInfo($SpellID), ['SpellID', 'Name', 'Description', 'icon'], ['id', 'name', 'description', 'icon']);
        parent::Encode($SpellData);
    }
}
?>