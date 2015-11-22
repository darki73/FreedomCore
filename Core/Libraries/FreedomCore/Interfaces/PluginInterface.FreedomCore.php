<?php

interface PluginInterface {

    /**
     * Get Name of Plugin
     * @return mixed
     */
    public function getPluginName();
    /**
     * Get Plugins Developer Information
     * @return mixed
     */
    public function getPluginDeveloper();
    /**
     * Get Version of the Plugin
     * @return mixed
     */
    public function getPluginVersion();
    /**
     * Set Plugin Name
     * @param $NameString
     * @return mixed
     */
    public function setPluginName($NameString);
    /**
     * Set Plugin Author
     * @param $AuthorArray
     * @return mixed
     */
    public function setPluginDeveloper($AuthorArray);
    /**
     * Set Plugin Version
     * @param $VersionString
     * @return mixed
     */
    public function setPluginVersion($VersionString);


    /**
     * Add Permission To Plugin
     * @param $PermissionID
     * @return mixed
     */
    public function addPermission($PermissionID);
    /**
     * Revoke Specified Permission from Plugin
     * @param $PermissionID
     * @return mixed
     */
    public function deletePermission($PermissionID);

    /**
     * Get List of Granted Permissions
     * @return mixed
     */
    public function getPermissions();
}


?>