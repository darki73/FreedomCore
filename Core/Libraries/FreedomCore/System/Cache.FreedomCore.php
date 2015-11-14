<?php

Class Cache
{
    private $CacheObject;
    private $CacheID;
    private $CacheData;
    private $CacheLocale;

    private $CachePath;
    private $CacheName;
    private $CacheExtension;
    private $CacheLife;

    private $cachedPath = null;
    private $packedData = null;

    public function __construct($Object)
    {
        $this->CachePath = getcwd().DS."Cache".DS."Compile".DS.$Object.DS;
        $this->CacheExtension = '.fnc';
        $this->CacheLife = 86400;
        $this->CacheObject = $Object;
        $this->CacheLocale = Utilities::GetLanguage(true);
    }

    /**
     * Prepare Data for Caching
     * @param $ID - ID of the item|character|guild|page to be cached
     * @param $Data - Data to be cached
     */
    public function prepareCache($ID, $Data = null)
    {
        $this->CacheID = $ID;
        $this->CacheData = $Data;
        $this->CacheName = substr(strtolower($this->CacheObject), 0, -1).'_'.$ID.'_'.$this->CacheLocale;
        $this->cachedPath = $this->CachePath.$this->CacheName.$this->CacheExtension;
    }

    /**
     * Perform initial checks and save all data to cache file
     */
    public function saveCache()
    {
        if($this->isCacheExists()){
            if($this->isUpdateRequired()){
                $this->updateCache();
            }
        } else {
            $this->packData()->writeFile();
        }
    }

    /**
     * Read Data from Cache File
     * @return mixed
     */
    public function readCache()
    {
        return $this->readFile()->unpackData();
    }

    /**
     * Check if cache file exists
     * @return bool
     */
    public function isCacheExists()
    {
        if(file_exists($this->cachedPath))
            return true;
        else
            return false;
    }

    /**
     * Check whether we need to update cache file
     * @return bool
     */
    public function isUpdateRequired()
    {
        $CacheCreated = filemtime($this->cachedPath);
        $CacheRenewal = $CacheCreated + $this->CacheLife;
        if(time() >= $CacheRenewal)
            return true;
        else
            return false;
    }

    /**
     * Update Cached Data
     */
    private function updateCache()
    {
        unlink($this->cachedPath);
        $this->packData()->writeFile();
    }

    /**
     * Prepare Data to be written to Cache file
     * @return string
     */
    private function packData()
    {
        $this->packedData = json_encode($this->CacheData, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * Extract Data from string to be used by the system
     * @return mixed
     */
    private function unpackData()
    {
        return json_decode($this->packedData, true);
    }

    /**
     * Create Cached File
     */
    private function writeFile()
    {
        $FileHandler = fopen($this->cachedPath, "w");
        fwrite($FileHandler, $this->packedData);
        fclose($FileHandler);
        $this->packedData = null;
        $this->cachedPath = null;
    }

    /**
     * Read Cache File
     */
    private function readFile()
    {
        $FileHandler = fopen($this->cachedPath, 'r');
        $this->packedData = fgets($FileHandler);
        fclose($FileHandler);
        return $this;
    }

    public function getVariables()
    {
        return [
            'object'    =>  $this->CacheObject,
            'id'        =>  $this->CacheID,
//            'data'      =>  $this->CacheData,
            'locale'    =>  $this->CacheLocale,
            'path'      =>  $this->CachePath,
            'name'      =>  $this->CacheName,
            'ext'       =>  $this->CacheExtension,
            'life'      =>  $this->CacheLife
        ];
    }
}
?>