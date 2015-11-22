<?php

Manager::LoadSystemInterface('PluginInterface');

Class Plugins implements PluginInterface {

    public $Name;
    public $Version;
    public $Author = [];
    public $Permissions = [];

    public function getPluginName(){
        return $this->Name;
    }
    public function getPluginDeveloper(){
        return $this->Author;
    }
    public function getPluginVersion(){
        return $this->Version;
    }

    public function setPluginName($NameString){
        $this->Name = $NameString;
    }
    public function setPluginDeveloper($AuthorArray){
        $this->Author = $AuthorArray;
    }
    public function setPluginVersion($VersionString){
        $this->Version = $VersionString;
    }


    public function addPermission($PermissionID){
        $this->Permissions[] = $this->AvailablePermissions($PermissionID);
    }
    public function deletePermission($PermissionID){

    }
    public function getPermissions(){
        return $this->Permissions;
    }

    private function AvailablePermissions($PermissionID = null)
    {
        $Permissions = [
            1       =>  'Read Configuration File',
            2       =>  'Edit Configuration File',
            3       =>  'Access Database Connection',
            4       =>  'Access Auth Database',
            5       =>  'Access Characters Database',
            6       =>  'Access World Database',
            7       =>  'Access Website Database',
            8       =>  'Access All Databases'
        ];

        return ['permission' => $PermissionID, 'description' => $Permissions[$PermissionID]];
    }
}

?>