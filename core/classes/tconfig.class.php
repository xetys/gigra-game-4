<?php
/**
 * 
 * Gigra Refact V3
 * @copyright 2011 (c) stytex.de 
 * @author David Steiman @ stytex.de
 * 
 * 2011: This code was rewritten completely for replacing Empire Space Source 
 * with new Source based on template system an mutlilanguage solution.
 * 
 * 
 * All rights reserved to David Steiman and under the rights of stytex.de
 */

class tConfig
{

    private static function createVar($asVarName,$asInitialSerializedValue)
    {
        $lfFile = fopen(ROOT_PATH."/tmp/{$asVarName}.tcnf","w");
        fwrite($lfFile,$asInitialSerializedValue);
        fclose($lfFile);
    }

    private static function checkVar($asVarName)
    {
        return file_exists(ROOT_PATH."/tmp/{$asVarName}.tcnf");
    }

    public static function getVar($asVarName,$aoDefault = null)
    {
        if(!self::checkVar($asVarName))
            self::createVar($asVarName,serialize($aoDefault));

        $lfFile = fopen(ROOT_PATH."/tmp/{$asVarName}.tcnf","r");
        $lsSerialized = "";
        while(!feof($lfFile))
            $lsSerialized .= fread($lfFile,1024);
        fclose($lfFile);
        

        return unserialize($lsSerialized);        
    }

    public static function setVar($asVarName,$aoValue)
    {
        $lfFile = fopen(ROOT_PATH."/tmp/{$asVarName}.tcnf","w");
        fwrite($lfFile,serialize($aoValue));
        fclose($lfFile);
    }
}

?>