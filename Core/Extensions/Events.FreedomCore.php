<?php

Class Events
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;


    public function __construct($VariablesArray)
    {
        Events::$DBConnection = $VariablesArray[0]::$Connection;
        Events::$CharConnection = $VariablesArray[0]::$CConnection;
        Events::$WConnection = $VariablesArray[0]::$WConnection;
        Events::$TM = $VariablesArray[1];
    }

    public static function CurrentEvents()
    {
        $Events = Events::getEvents();
        $CurrentDate = date('Y-m-d');
        $CurrentEvents = [];
        foreach($Events as $Event){
            if(isset($Event['next_start'])){
                $EventBeginDate = date('Y-m-d', strtotime($Event['next_start']));
                $LimitStartDate = Events::setTimeLimitStart('- 1 week', $CurrentDate);
                $LimitEndDate = Events::setTimeLimitEnd('+ 1 day', $CurrentDate);
                if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate) && ($CurrentDate < $Event['next_end']))
                    $CurrentEvents[] = $Event;
            }
        }

        return $CurrentEvents;
    }

    public static function ClosestEvents()
    {
        $Events = Events::getEvents();
        $ClosestEvents = [];
        $CurrentDate = date('Y-m-d');

        // Soon
        foreach($Events as $Event){
            if(isset($Event['next_start'])){
                $EventBeginDate = date('Y-m-d', strtotime($Event['next_start']));
                $LimitStartDate = Events::setTimeLimitStart('- 1 week', $CurrentDate);
                $LimitEndDate = Events::setTimeLimitEnd('+ 1 day', $CurrentDate);
                if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate) && ($CurrentDate < $Event['next_end']))
                    $ClosestEvents['today'][] = $Event;
            }
        }

        // This Week
        foreach($Events as $Event){
            if(isset($Event['next_start'])){
                $EventBeginDate = date('Y-m-d', strtotime('+ 1 week', strtotime($Event['next_start'])));
                $LimitStartDate = Events::setTimeLimitStart('+ 2 day', $CurrentDate);
                $LimitEndDate = Events::setTimeLimitEnd('+ 3 week', $CurrentDate);
                if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate)){
                    $ClosestEvents['soon'][] = $Event;
                }
            }
        }


        return $ClosestEvents;
    }

    private static function setTimeLimitStart($LimitString, $FromTime)
    {
        return date('Y-m-d', strtotime(''.$LimitString.'', strtotime($FromTime)));
    }

    private static function setTimeLimitEnd($LimitString, $FromTime)
    {
        return date('Y-m-d', strtotime(''.$LimitString.'', strtotime($FromTime)));
    }

    public static function getEventData($EventName){
        $Statement = Events::$DBConnection->prepare('SELECT * FROM events WHERE event_name = :eventname');
        $Statement->bindParam(':eventname', $EventName);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result = $Statement->fetch(PDO::FETCH_ASSOC);
            $Exploded = explode('-', $EventName);
            $EventNameComplete = "";
            for($i = 0; $i < count($Exploded); $i++){
                $EventNameComplete .= ucfirst($Exploded[$i])." ";
            }
            $Result['event_place'] = Events::$TM->getConfigVars($Result['event_place']);
            $Result['event_quests'] = Events::$TM->getConfigVars($Result['event_quests']);
            $Result['event_entertainment'] = Events::$TM->getConfigVars($Result['event_entertainment']);
            $Result['event_merchants'] = Events::$TM->getConfigVars($Result['event_merchants']);
            if($Result['event_currency_present'])
                $Result['event_currency'] = Events::$TM->getConfigVars($Result['event_currency']);

            $EventCollectibles = [
                'Items'     =>  Events::GetEventItems($Result['event_items']),
                'Mounts'    =>  Events::GetEventMounts($Result['event_mounts']),
                'Pets'      =>  Events::GetEventPets($Result['event_pets']),
                'Toys'      =>  Events::GetEventToys($Result['event_toys'])
            ];
            $AdditionalVars = ['Intro_Header', 'Intro_Footer', 'What_To_Do'];
            foreach($AdditionalVars as $Item)
                $Result[strtolower($Item)] = Events::$TM->getConfigVars('Events_'.str_replace(' ', '_', $EventNameComplete.$Item));
            $Result['link'] = $EventName;
            $Result['name'] = $EventNameComplete;
            $Result['collectibles'] = $EventCollectibles;
            $Achievements = Events::GetAchievements($Result['event_category']);
            if($Achievements)
                $Result['achievements'] = $Achievements;
            return $Result;
        }
    }

    private static function GetAchievements($Category){
        $Statement = Events::$DBConnection->prepare('SELECT fa.*, LOWER(fsi.iconname) as icon FROM freedomcore_achievement fa LEFT JOIN freedomcore_spellicons fsi ON fa.icon = fsi.id WHERE category = :cat GROUP BY name_loc0');
        $Statement->bindParam(':cat', $Category);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function GetEventItems($String)
    {
        $ItemsArray = [];
        if(Text::IsNull($String))
            return false;
        else {
            $Exploded = explode(',', $String);
            foreach($Exploded as $Entry){
                $GetData = Items::GetItemInfo($Entry);
                if($GetData != false)
                    $ItemsArray[] = $GetData;
            }
        }

        return $ItemsArray;
    }

    private static function GetEventMounts($String)
    {
        $MountsArray = [];
        if(Text::IsNull($String))
            return false;
        else {
            $Exploded = explode(',', $String);
            foreach($Exploded as $Entry){
                $GetData = Items::GetItemInfo($Entry);
                if($GetData != false)
                    $MountsArray[] = $GetData;
            }
        }

        return $MountsArray;
    }

    private static function GetEventPets($String)
    {
        $PetsArray = [];
        if(Text::IsNull($String))
            return false;
        else {
            $Exploded = explode(',', $String);
            foreach($Exploded as $Entry){
                $GetData = Items::GetItemInfo($Entry);
                if($GetData != false)
                    $PetsArray[] = $GetData;
            }
        }

        return $PetsArray;
    }

    private static function GetEventToys($String)
    {
        $ToysArray = [];
        if(Text::IsNull($String))
            return false;
        else {
            $Exploded = explode(',', $String);
            foreach($Exploded as $Entry){
                $GetData = Items::GetItemInfo($Entry);
                if($GetData != false)
                    $ToysArray[] = $GetData;
            }
        }

        return $ToysArray;
    }

    public static function getCurrentEvent($Events)
    {
        $DisplayOnly = [327, 423, 181, 201, 341, 321, 398, 372, 324, 409, 404, 141];
        $CurrentEvent = [];
        foreach($Events as $Event){
            if(isset($Event['next_start']) && isset($Event['next_end'])) {
                $EventStart = strtotime($Event['next_start']);
                $EventEnd = strtotime($Event['next_end']);
                $TimeNow = time();
                if($EventEnd > $TimeNow && $TimeNow > $EventStart)
                    if(in_array($Event['holiday'], $DisplayOnly))
                        $CurrentEvent = $Event;
            }
        }

        if(!empty($CurrentEvent)){
            $Exploded = explode('-', $CurrentEvent['link']);
            $EventSmartyName = "Events_Page_";
            for($i = 0; $i < count($Exploded); $i++){
                if($i != (count($Exploded) - 1)){
                    $EventSmartyName .= ucfirst($Exploded[$i]).'_';
                } else {
                    $EventSmartyName .= ucfirst($Exploded[$i]);
                }
            }

            $CurrentEvent['description_text'] = Events::$TM->getConfigVars($EventSmartyName);
        }

        return $CurrentEvent;
    }

    public static function getEvents()
    {
        $Events = Events::$WConnection->prepare('SELECT * FROM game_event');
        $Events->execute();
        $Events = $Events->fetchAll(PDO::FETCH_ASSOC);
        $Iterations = 0;
        foreach($Events as $Event){
            $Events[$Iterations]['each_days'] = Events::MinutesToDays($Event['occurence']);
            $DaysLength = Events::MinutesToDays($Event['length']);
            if($DaysLength < 1)
                $DLength = 1;
            else
                $DLength = round(Events::MinutesToDays($Event['length']));
            $Events[$Iterations]['length_days'] = $DLength;
            $NextStart = Events::EventStartCalculator($Event['start_time'], Events::MinutesToDays($Event['occurence']));
            $Events[$Iterations]['next_start'] = $NextStart;
            $Events[$Iterations]['next_end'] = Events::EventEndCalculator($NextStart, $DLength);
            $Exploded = explode(' ', $Event['description']);
            $EName = "";
            foreach($Exploded as $Part)
                $EName .= ucfirst($Part)." ";

            $Events[$Iterations]['description'] = trim($EName);
            if($Event['holiday'] == 0)
                unset($Events[$Iterations]);
            if($Event['start_time'] == '0000-00-00 00:00:00' && $Event['end_time'] == '0000-00-00 00:00:00')
                unset($Events[$Iterations]);
            if(strstr($Event['description'], 'Trigger'))
                unset($Events[$Iterations]);
            if(strstr($Event['description'], '(')) {
                $EventName = explode('(', $Event['description']);
                $EventName = trim($EventName[0]);
            } else {
                $EventName = trim($Event['description']);
            }
            $Events[$Iterations]['link'] = str_replace('\'', '', str_replace(' ', '-', strtolower($EventName)));
            $Iterations++;
        }

        return $Events;
    }

    private static function MinutesToDays($Minutes)
    {
        return $Minutes/1440;
    }

    private static function GetYear($Date)
    {
        return explode('-', $Date)[0];
    }

    private static function EventStartCalculator($FirstStart, $EachDays)
    {
        $CurrentTime = time();
        $StartTime = strtotime($FirstStart);
        $StartYear = Events::GetYear($FirstStart);
        if($EachDays > 363 && $EachDays < 367){
            $YearDifference = Events::GetYear(date('Y-m-d H:i:s', $CurrentTime)) - $StartYear;
            return date('Y-m-d H:i:s', strtotime('+ ' . $YearDifference . ' years', $StartTime));
        } else {
            $dateStart = new DateTime($FirstStart);
            $dateEnd  = new DateTime(date('Y-m-d H:i:s', $CurrentTime));
            $dateDiff = $dateStart->diff($dateEnd);
            $OccuredTimes = floor($dateDiff->days/$EachDays);
            $NextOccurence = $dateDiff->days/$EachDays - $OccuredTimes;
            if($OccuredTimes != 0)
                return date('Y-m-d H:i:s', strtotime('+ ' . $EachDays * $OccuredTimes . ' days', $StartTime));
        }
    }

    private static function EventEndCalculator($StartDate, $Duration)
    {
        return date('Y-m-d H:i:s', strtotime('+ ' . $Duration .' days', strtotime($StartDate)));
    }

    public static function sortByDate($Array)
    {
        foreach ($Array as $key => $row) {
            if(isset($row['next_start']))
                $date[$key] = $row['next_start'];
        }
        $Prepare = [];
        $Final = [];

        foreach($date as $key => $value){
            $Prepare[] = [
                'index' =>  $key,
                'date'  =>  $value,
                'time'  =>  strtotime($value)
            ];
        }

        usort($Prepare, function($a, $b) {
            return $a['time'] - $b['time'];
        });

        foreach($Prepare as $Item){
            $Final[] = $Array[$Item['index']];
        }
        return $Final;
    }
}
