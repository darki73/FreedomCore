<?php

Class String
{
    public static function GetTextBetween($String, $First, $Last)
    {
        $FirstFound = strpos($String, $First)+1;
        $LastFound = strrpos($String, $Last);
        $TextLength = $LastFound-$FirstFound;
        $Text = substr($String, $FirstFound, $TextLength);
        return $Text;
    }

    public static function GetTextBefore($String, $First)
    {
        $FirstFound = strpos($String, $First)-1;
        $Text = substr($String, 0, $FirstFound);
        return $Text;
    }

    public static function MASearch($array, $field, $value)
    {
        foreach($array as $key => $item)
        {
            if ( $item[$field] === $value )
                return $key;
        }
        return false;
    }

    public static function IsNull($String)
    {
        if(is_null($String))
            return true;
        elseif($String == "")
            return true;
        elseif($String == " ")
            return true;
        else
            return false;
    }

    public static function Request()
    {
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
    }

    public static function Match($ArgOne, $ArgTwo)
    {
        if($ArgOne == $ArgTwo)
            return true;
        else
            return false;
    }

    public static function PrettyPrint($Array)
    {
        echo "<pre>";
        print_r($Array);
        echo "</pre>";
    }

    public static function MassUnset($Array, $Unset)
    {
        $ArrayIndex = 0;
        foreach($Array as $Item)
        {
            foreach($Unset as $Delete)
                unset($Array[$ArrayIndex][$Delete]);
            $ArrayIndex++;
        }
        return $Array;
    }

    public static function UnshiftAssoc(&$Array, $Key, $Value)
    {
        $Array = array_reverse($Array, true);
        $Array[$Key] = $Value;
        return array_reverse($Array, true);
    }

    public static function NiceNumbers($n) {
        $n = (0+str_replace(",","",$n));
        if(!is_numeric($n)) return false;
        if($n>1000000) return round(($n/1000000),1).' M';
        else if($n>1000) return round(($n/1000),1).' K';

        return number_format($n);
    }

    public static function GetTimeDiff($timestamp)
    {
        $seconds = time()-$timestamp;
        $HowLongAgo = "";
        $days = intval(intval($seconds) / (3600*24));
        if($days> 0)
            $HowLongAgo .= str_replace(' ', '', "$days d").' ';
        $hours = (intval($seconds) / 3600) % 24;
        if($hours > 0)
            $HowLongAgo .= str_replace(' ', '', "$hours h").' ';
        $minutes = (intval($seconds) / 60) % 60;
        if($minutes > 0)
            $HowLongAgo .= str_replace(' ', '', "$minutes m").' ';
        $seconds = intval($seconds) % 60;
        if ($seconds > 0)
            $HowLongAgo .= str_replace(' ', '', "$seconds s").' ';

        return $HowLongAgo;
    }

    public static function SecondsToTime($seconds) {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%ad %hh %im %ss');
    }

    public static function MoneyToCoins($money)
    {
        $coins = array();
        if($money >= 10000)
        {
            $coins['gold'] = floor($money / 10000);
            $money = $money - $coins['gold']*10000;
        }
        if($money >= 100)
        {
            $coins['silver'] = floor($money / 100);
            $money = $money - $coins['silver']*100;
        }
        if($money > 0)
            $coins['copper'] = $money;
        return $coins;
    }

    public static function FindClosestKey($Array, $Key)
    {
        foreach($Array as $AKey=>$AValue)
        {
            if($Key >= $AValue['lowest'] && $Key <= $AValue['highest'])
                return $AKey;
        }
    }

    public static function Declension($string, $ch1, $ch2, $ch3)
    {
        $ff=Array('0','1','2','3','4','5','6','7','8','9');
        if(substr($string,-2, 1)==1 AND strlen($string)>1)
            $ry=array("0 $ch3","1 $ch3","2 $ch3","3 $ch3" ,"4 $ch3","5 $ch3","6 $ch3","7 $ch3","8 $ch3","9 $ch3");
        else
            $ry=array("0 $ch3","1 $ch1","2 $ch2","3 $ch2","4 $ch2","5 $ch3"," 6 $ch3","7 $ch3","8 $ch3"," 9 $ch3");
        $string1=substr($string,0,-1).str_replace($ff, $ry, substr($string,-1,1));
        return $string1;
    }

    public static function ProtectionCode($MainString)
    {
        return sha1($MainString.mt_rand(10000,99999));
    }

    public static function UnsetMany($Array, $UnsetArray)
    {
        foreach($UnsetArray as $Item)
            unset($Array[$Item]);
        return $Array;
    }

    public static function RemapArray($Array, $From, $To)
    {
        $NewArray = [];
        foreach($From as $Key => $Value)
            $NewArray[$To[$Key]] = $Array[$Value];
        return $NewArray;
    }

    public static function UnsetAllBut($Save, $Array, $Dimensions = 1)
    {
        $FinalArray = array();
        $ArrayItems = count($Array);
        $StartingIndex = 0;
        if($Dimensions == 1)
        {
            foreach($Array as $Key=>$Value)
            {
                if($Key != $Save)
                    unset($Array[$Key]);
                $FinalArray[] = $Array[$Save];
            }
        }
        elseif($Dimensions == 2)
        {
            foreach($Array as $Item)
            {
                foreach($Item as $Key=>$Value)
                {
                    if($Key != $Save)
                        unset($Array[$Key]);
                    if(!in_array($Item[$Save], $FinalArray))
                        $FinalArray[] = $Item[$Save];
                }
            }
        }
        return $FinalArray;
    }

    public static function GenerateCaptcha()
    {
        flush();
        ob_clean();
        if(isset($_SESSION['generated_captcha']) && $_SESSION['generated_captcha'] != '')
            Session::UnsetKeys(array('generated_captcha'));
        $InitialString = str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890");
        $RandomString = substr($InitialString,0,9);
        $_SESSION['generated_captcha'] = $RandomString;
        Session::UpdateSession($_SESSION);
        $CreateBlankImage = ImageCreate (200, 70) or die ("Cannot Initialize new GD image stream");
        $BackgroundColor = ImageColorAllocateAlpha($CreateBlankImage, 255, 255, 255, 127);
        imagefill($CreateBlankImage,0,0,0x7fff0000);
        $BackgroundColor = ImageColorAllocate($CreateBlankImage, 204, 255, 51);
        $TextColor = ImageColorAllocate ($CreateBlankImage, 51, 51, 255);
        ImageString($CreateBlankImage,5,50,25,$RandomString,$TextColor);
        ImagePng($CreateBlankImage);
    }
}

?>