<?php

Class DemoPlugin extends Plugins {

    public function __construct()
    {
        parent::setPluginName('FreedomCore Demo Plugin');
        parent::setPluginDeveloper(['author' => 'darki73', 'website' => 'https://freedomcore.ru']);
        parent::setPluginVersion('1.0.0.0');

        $this->InitializePermissionsManager();
    }

    private function InitializePermissionsManager()
    {
        $RequiredPermissions = [1, 2, 3, 8];
        foreach($RequiredPermissions as $Permission)
            parent::addPermission($Permission);
    }
}

global $DemoPlugin;
$DemoPlugin = new DemoPlugin();
