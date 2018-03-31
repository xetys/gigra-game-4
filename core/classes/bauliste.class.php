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
 
 
class v3Bauliste
{
    
    protected $iaRows = array();
    protected $aiSumTime = 0;
    protected $isCoords = "";
    protected $ibWriteMode = false;
    protected $iaRest = array();
    
    public function __construct($asCoords,$abWriteMode = false)
    {
        $this->isCoords = $asCoords;
        $this->ibWriteMode = $abWriteMode;//wird zB durch den Eventhandler angegeben, damit dann keine doppelten Schiffs fertigstellungen erfolgen
        $this->aiSumtime['S'] = 0;
        $this->aiSumtime['V'] = 0;
        
        $this->updateAll();
    }
    function deleteRow($aiID)
    {
        gigraDB::db_open()->query("DELETE FROM produktion WHERE id = '{$aiID}'");   
    }
    function updateAll()
    {
        $lodb = gigraDB::db_open();
        
        $lsQuery = "SELECT id,sid,count,typ,bauzeit,ptime,pos,coords FROM produktion WHERE coords = '{$this->isCoords}' ORDER BY ptime ASC";
        $this->iaRows = array();
        
        $lodb->query($lsQuery);
        while($laRow = $lodb->fetch("assoc"))
        {
            $this->iaRows[] = $laRow;
        }
        foreach($this->iaRows as $laBauRow)
        {
            $this->aiSumtime[$laBauRow['typ']] += ($laBauRow['bauzeit'] * $laBauRow["count"]);
            $liZeitVergangen = time() > $laBauRow['ptime'] ? time() - $laBauRow['ptime'] : 0;
            $this->aiSumtime[$laBauRow['typ']] -= min($liZeitVergangen,($laBauRow['bauzeit'] * $laBauRow["count"]));
            //$this->aiSumtime[$laBauRow['typ']] -= $liZeitVergangen;
            
            if($liZeitVergangen > 0) $this->iaRest[$laBauRow['typ']] = $laBauRow['bauzeit'] - $liZeitVergangen;
            //echo $this->iaRest[$laBauRow['typ']] . "<br>";
            if($liZeitVergangen > 0 && $this->ibWriteMode)//Das hier sollte nur der EH abfeuern!!!
            {
                $liFertig = floor($liZeitVergangen / max(1,$laBauRow['bauzeit']));
                
                
                $liFertig = min($liFertig,$laBauRow['count']);
                
                
                if($liFertig > 0)
                {
                    //schreibe schiffe   
                    
                    add_schiffe($this->isCoords,array($laBauRow['sid'] => $liFertig),$laBauRow['typ']);
                    $liNewCount = $laBauRow['count'] - $liFertig;
                    
                    if($liNewCount == 0)
                        $this->deleteRow($laBauRow['id']);
                    else
                    {
                        $lodb->query("UPDATE produktion SET ptime = UNIX_TIMESTAMP(), count = '$liNewCount' WHERE id = '{$laBauRow['id']}'");
                    }
                }
            }
        }
        
        //ausgabe
    }
    
    function add($aiSid,$aiCount,$aiProdTime,$asType = 'S')
    {
        $lodb = gigraDB::db_open();
        
        
        $liStartTime = time() + $this->aiSumtime[$asType];//Erklärung, updateAll wird immer durchgeführt, wir ermitteln einfach was gebaut werden sollte
        $liBauZeit = $aiProdTime;
        $lsTyp = $asType;
        
        $lodb->query("INSERT INTO produktion SET coords = '{$this->isCoords}', typ = '$asType', sid = '$aiSid', count = '$aiCount' ,ptime = '{$liStartTime}', bauzeit = '{$liBauZeit}'");
        
        $this->updateAll();
    }
    
    function remove($aiID)
    {
        global $_SHIP, $_VERT;
        $lodb = gigraDB::db_open();
        
        $laRow = $lodb->getOne("SELECT sid,count,typ,coords FROM produktion WHERE id = '{$aiID}'");
        
        //Fehler abfangen
        if(!$laRow)
            return false;
        $res = array(0,0,0,0);
        $CONST = $laRow['typ'] == "V" ? $_VERT : $_SHIP;
        $res[0] = $laRow['count'] * $CONST[$laRow['sid']][1];
        $res[1] = $laRow['count'] * $CONST[$laRow['sid']][2];
        $res[2] = $laRow['count'] * $CONST[$laRow['sid']][3];
        $res[3] = $laRow['count'] * $CONST[$laRow['sid']][4];
        
        add_res($res,$laRow['coords']);
        $lodb->query("DELETE FROM produktion WHERE id = '{$aiID}'");
        $this->retime($laRow['typ']);
        
        return true;
    }
    function retime($asTyp)
    {
        $lodb = gigraDB::db_open();
        $lodb2 = gigraDB::db_open();
        
        //
        $lodb->query("SELECT id,bauzeit,ptime,count FROM produktion WHERE coords = '".$this->isCoords."' AND typ = '$asTyp' ORDER by ptime ASC");
        $time = time();
        while($laRow = $lodb->fetch())
        {
            $lodb2->query("UPDATE produktion SET ptime = '{$time}' WHERE id = '{$laRow['id']}'");
            $time += $laRow['count'] * $laRow['bauzeit'];
        }
    }
    function listItems($asType = 'S')
    {
        $laReturn = array();

        foreach($this->iaRows as $laRow)
        {
            if($laRow['typ'] == $asType)
            {
                $laReturn[] = array(
                    "id" => $laRow["id"],
                    "sid" => $laRow["sid"],
                    "count" => $laRow["count"],
                    "time" => $laRow["bauzeit"],
                    "rest" => $this->iaRest[$laRow['typ']] == 0 ? $laRow['bauzeit'] : $this->iaRest[$laRow['typ']]
                    );
                    $this->iaRest[$laRow['typ']] = 0;
            }
        }
        return $laReturn;
    }
    
    public static function getFromID($aiID)
    {
        $laRows = gigraDB::db_open()->getOne("SELECT coords FROM produktion WHERE id = '{$aiID}'");
        if(!$laRows == false)
            return new v3Bauliste($laRows[0],true);
    }
}
?>
