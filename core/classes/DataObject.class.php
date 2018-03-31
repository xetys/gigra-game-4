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


/*abstract*/ class DataObject
{
    protected $ioDB;

    protected $isTable;

    protected $isIndex;
    
    protected $ioIndexVal;

    protected $iaFields = array();
    
    protected $iaModified = array();
    
    public function __construct($asTable,$asIndex,$aoIndexVal)
    {
        $this->ioDB = gigraDB::db_open();
        $this->isTable = $asTable;
        $this->isIndex = $asIndex;
        $this->ioIndexVal = $aoIndexVal;
        
        
        $lsQuery = "SELECT * FROM `%s` WHERE `%s` = '{$aoIndexVal}';";


        $this->ioDB->query(sprintf($lsQuery,$asTable,$asIndex));
        
        $laRow = $this->ioDB->fetch("assoc");
        
        foreach($laRow as $lsKey => $loVal)
        {
            if($lsKey != $asIndex)
                $this->iaFields[strtolower($lsKey)] = $loVal;
        }
    }

    public function __call($asFuncName,$aaParams)
    {
        if(substr($asFuncName,0,3) == "get")
        {
            $lsKey = strtolower(substr($asFuncName,3));
            if(isset($this->iaFields[$lsKey]))
                return $this->iaFields[$lsKey];
        }
        else if(substr($asFuncName,0,3) == "set")
        {
            $lsKey = strtolower(substr($asFuncName,3));
            if(isset($this->iaFields[$lsKey]))
            {
                $this->iaFields[$lsKey] = $aaParams[0];
                $this->iaModified[] = $lsKey;
                return true;
            }
        }
        
        return null;
    }
    
    public function save()
    {
        $lsQuery = "UPDATE `%s` SET %s WHERE `%s` = '%s'";
        
        if(count($this->iaModified) == 0)
            return;
        $laSetString = array();
        foreach($this->iaModified as $lsKey => $loVal)
        {
            $laSetString[] .= "`{$lsKey}` = '".$this->ioDB->escape($loVal)."'";
        }
        
        $this->ioDB->query(sprintf($lsQuery,$this->isTable,implode(", ",$laSetString),$this->isIndex,$this->ioIndexVal));
    }
    
    
}


