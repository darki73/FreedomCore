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
            $EventBeginDate = date('Y-m-d', strtotime($Event['next_start']));
            $LimitStartDate = Events::setTimeLimitStart('- 1 week', $CurrentDate);
            $LimitEndDate = Events::setTimeLimitEnd('+ 1 day', $CurrentDate);
            if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate) && ($CurrentDate < $Event['next_end']))
                $CurrentEvents[] = $Event;
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
            $EventBeginDate = date('Y-m-d', strtotime($Event['next_start']));
            $LimitStartDate = Events::setTimeLimitStart('- 1 week', $CurrentDate);
            $LimitEndDate = Events::setTimeLimitEnd('+ 1 day', $CurrentDate);
            if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate) && ($CurrentDate < $Event['next_end']))
                $ClosestEvents['today'][] = $Event;
        }

        // This Week
        foreach($Events as $Event){
            $EventBeginDate = date('Y-m-d', strtotime('+ 1 week', strtotime($Event['next_start'])));
            $LimitStartDate = Events::setTimeLimitStart('+ 2 day', $CurrentDate);
            $LimitEndDate = Events::setTimeLimitEnd('+ 3 week', $CurrentDate);
            if(($LimitStartDate < $EventBeginDate) && ($EventBeginDate < $LimitEndDate)){
                $ClosestEvents['soon'][] = $Event;
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

    private static function getEvents()
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
            if($Event['holiday'] == 0)
                unset($Events[$Iterations]);
            if($Event['start_time'] == '0000-00-00 00:00:00' && $Event['end_time'] == '0000-00-00 00:00:00')
                unset($Events[$Iterations]);
            if(strstr($Event['description'], 'Trigger'))
                unset($Events[$Iterations]);
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
}
