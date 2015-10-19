<?php
require_once('Core/Core.php');
Manager::LoadExtension('News', array($Database, $Smarty));
$Smarty->assign('Slideshow', News::GetSlideshowItems());
$Smarty->assign('News', News::GetAllNews());
$Smarty->assign('Page', Page::Info('homepage', array('bodycss' => 'homepage news', 'pagetitle' => '')));
$Smarty->display('main');
?>