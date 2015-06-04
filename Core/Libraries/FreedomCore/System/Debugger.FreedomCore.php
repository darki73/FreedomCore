<?php

Class Debugger extends FreedomCore
{
	public static function ReportError($ErrorCategory, $ErrorID, $CausedBy, $ReportAs='html')
     {
          if($ReportAs == 'html')
          {
               echo '<strong>'.Self::ErrorCategory($ErrorCategory).': </strong> <i>Error #'.$ErrorID.':</i> '.Self::ErrorID($ErrorCategory, $ErrorID).' <strong><font color="red">'.$CausedBy.'</font></strong>';
          }
     }

     public static function ErrorCategory($Category)
     {
          $DefinedCategories = array(
          '1' => 'FS Error',
          '2' => 'License Error',
          '3' => '',
          '4' => '',
          '5' => '',
          );

          $InCat = $DefinedCategories[$Category];
          return $InCat;
     }

     public static function ErrorID($Category, $ID)
     {
          $ErrorsID = array();
          if($Category == 1)
          {
               $ErrorsID = array(
               '1' => 'Unable to load Extension',
               '2' => 'Unable to load',
               );
          }
          elseif($Category == 2)
          {
               $ErrorsID = array(
               '1' => 'Invalid License Code',
               '2' => 'This License Code was already used',
               '3' => 'This License Code was issued to be used on other domain',
               '4' => 'License Expired, visit <a href="https://secure.freedomcore.eu/payment/">FreedomCore Payment</a> gate to renew it',
               '5' => 'Nulled version! We are adding you to our database',
               '6' => 'License Key is not set',
               '7' => 'Username is not set',
               '8' => 'Invalid Username',
               '9' => 'Invalid Hash',
               );
          }
          elseif($Category == 3)
          {
               $ErrorsID = array(
               ''
               );
          }
          elseif($Category == 4)
          {
               $ErrorsID = array(
               ''
               );
          }
          elseif($Category == 5)
          {
               $ErrorsID = array(
               ''
               );
          }

          $OError = $ErrorsID[$ID];
          return $OError;
     }
}

?>