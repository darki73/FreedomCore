<?php

function smarty_modifier_relative_date_short($timestamp, $days = false, $format = "M j, Y") {

    if (!is_numeric($timestamp)) {
        // It's not a time stamp, so try to convert it...
        $timestamp = strtotime($timestamp);
    }

    if (!is_numeric($timestamp)) {
        // If its still not numeric, the format is not valid
        return false;
    }

    // Calculate the difference in seconds
    $difference = time() - $timestamp;

    // Check if we only want to calculate based on the day
    if ($days && $difference < (60*60*24)) {
        return "Today";
    }
    if ($difference < 3) {
        return "Just now";
    }
    if ($difference < 60) {
        return $difference . " seconds ago";
    }
    if ($difference < (60*2)) {
        return "1 minute ago";
    }
    if ($difference < (60*60)) {
        return intval($difference / 60) . "m ";
    }
    if ($difference < (60*60*2)) {
        return "1 hour ago";
    }
    if ($difference < (60*60*24)) {
        return intval($difference / (60*60)) . "h ";
    }
    if ($difference < (60*60*24*2)) {
        return @date($format, $timestamp);
    }

    // More than a year ago, just return the formatted date
    return @date($format, $timestamp);

}

?>