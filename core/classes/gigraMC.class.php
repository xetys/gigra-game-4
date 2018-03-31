<?php

class gigraMC {

    var $iTtl; // Time To Live
    var $bEnabled = false; // Memcache enabled?
    var $oCache = null;

    // constructor
    function __construct($aiCacheTime = 20) {
        if (class_exists('Memcached')) {
            $this->oCache = new Memcached();
            $this->bEnabled = true;
            if (!$this->oCache->addServer('localhost', 11211))  { // Instead 'localhost' here can be IP
                $this->oCache = null;
                $this->bEnabled = true;
                $this->iTtl = $aiCacheTime;
            }
        }
        else
            die("no mc");
    }

    // get data from cache server
    function getData($sKey) {
        $vData = $this->oCache->get($sKey.Uid());
        return false === $vData ? null : $vData;
    }

    // save data to cache server
    function setData($sKey, $vData) {
        //Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib).
        return $this->oCache->set($sKey.Uid(), $vData, $this->iTtl);
    }

    // delete data from cache server
    function delData($sKey) {
        return $this->oCache->delete($sKey.Uid());
    }
    
    public static function clearCache($asName)
    {
        $loMC = new gigraMC(0);
        $loMC->delData($asName);
    }
}

?>
