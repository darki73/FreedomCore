<?php

Class Text
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

    public static function Search($array, $search)
    {

        $result = array();

        foreach ($array as $key => $value)
        {
            foreach ($search as $k => $v)
                if (!isset($value[$k]) || $value[$k] != $v)
                    continue 2;
            $result[] = $key;
        }

        return $result;
    }

    public static function Like($Array, $Search)
    {
        $Position = array_filter($Array, function ($item) use ($Search) {
            if (stripos($item, $Search) !== false) {
                return true;
            }
            return false;
        });
        return $Position;
    }

    public static function ASearch($Array, $Item){
        foreach($Array as $Key => $Value)
            if(trim($Value) == trim($Item))
                return $Key;
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

    public static function toJson($Array, $Parameters = [])
    {
        $PList = 0;
        for($i = 0; $i < count($Parameters); $i++){
            $PList += constant($Parameters[$i]);
        }
        echo json_encode($Array, $PList);
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

    public static function Truncate($String, $Length, $StopAnywhere=false) {
        if (strlen($String) > $Length) {
            $String = substr($String,0,($Length -3));
            if ($StopAnywhere)
                $String .= '...';
            else
                $String = substr($String,0,strrpos($String,' ')).'...';
        }
        return $String;
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

    public static function IsRequestSet($Request, $KeysToCheck){
        $Validated = false;
        foreach($KeysToCheck as $Key){
            if(isset($Request[$Key]))
                $Validated = true;
            else {
                $Validated = false;
                break;
            }
        }
        return $Validated;
    }

    public static function UniqueMulti($Array)
    {
        $Array = array_unique($Array);
        return array_map('array_values', $Array);
    }

    public static function UniqueSingle($Array)
    {
        $Array = array_unique($Array);
        $Array = array_values($Array);
        return $Array;
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

    public static function SimpleJson($Code, $Key, $Text)
    {
        return json_encode(['code' => $Code, $Key => $Text]);
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

    public static function GenerateSlug($String, $Options = array()) {
        $String = mb_convert_encoding((string)$String, 'UTF-8', mb_list_encodings());

        $Defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );

        // Merge options
        $Options = array_merge($Defaults, $Options);

        $CharMap = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );

        $String = preg_replace(array_keys($Options['replacements']), $Options['replacements'], $String);
        if ($Options['transliterate'])
            $String = str_replace(array_keys($CharMap), $CharMap, $String);
        $String = preg_replace('/[^\p{L}\p{Nd}]+/u', $Options['delimiter'], $String);
        $String = preg_replace('/(' . preg_quote($Options['delimiter'], '/') . '){2,}/', '$1', $String);
        $String = mb_substr($String, 0, ($Options['limit'] ? $Options['limit'] : mb_strlen($String, 'UTF-8')), 'UTF-8');
        $String = trim($String, $Options['delimiter']);

        return $Options['lowercase'] ? mb_strtolower($String, 'UTF-8') : $String;
    }
}

?>