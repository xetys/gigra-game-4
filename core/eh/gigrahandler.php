<?php

define("GIGRA_INTERN", true);
define("HANDLER_MODE",true);

if(count($argv) < 2)
    die("Bitte mal eine Zahl angeben, enstprechend der RundenID");
else
{
    $_RUNDE = (int)$argv[1];
    
}

include_once "../core.php";
include_once "handler_funcs.php";
include_once "build_handler_funcs.php";
include_once "cron_handler_funcs.php";
include_once "fleet_handler_funcs.php";

include_once "Thread.class.php";

class GigraHandler extends Thread
{
    private $iaHandlers = array();
    
    private $iaLockedEvents = array();
    
    public function log($sMessage,$color = 'no')
    {
		ehLog($sMessage,$color);
	}   
    
    private function cleanUp()
    {
        foreach($this->iaHandlers as $liK => $loHandler)
        {
            if(!$loHandler->isAlive())
            {
                unset($this->iaHandlers[$liK]);
                $this->log("Handler $liK wurde bereinigt","purple");
            }
        }
        
        $this->freeEvents();
    }
    
    private function lockEvent($asEventId,$aiLockTime = 10)
    {
        $this->iaLockedEvents[$asEventId] = time() + $aiLockTime;
    }
    
    private function eventLocked($asEventId)
    {
        return isset($this->iaLockedEvents[$asEventId]);
    }
    
    private function freeEvents()
    {
        foreach($this->iaLockedEvents as $lsEventId => $liTime)
            if($liTime < time())
                unset($this->iaLockedEvents[$lsEventId]);
    }
    
    public function run()
    {
        global $_RUNDE;
        
        $lodb = gigraDB::db_open();
        
        $this->log("Der Gigra Evendhandler Version Refact 1 fuer Runde $_RUNDE wurde gestartet","cyan");
        $lbCron = false;
        while(1)
        {
            //Bauvorgänge, Flotten usw
            $laEvents = $this->listEvents();
            foreach($laEvents as $laEvent)
            {
                if($laEvent["type"] == "stop")
                {
                    $lodb->query("DELETE FROM events WHERE id = '{$laEvent["id"]}'");
                    $this->log("Erhalte Befehl zum Herunterfahren","red");
                    $this->ibKillMe = true;
                    
                    //killProcessAndChilds(posix_getppid(),9);
                    system("killall -9 php -q gigrahandler.php");
                }
                
                $lsEventId = $laEvent["type"]."_".$laEvent["id"];
                if(!$this->eventLocked($lsEventId))
                {
                    if($laEvent["type"] == "v3prod")
                        $this->lockEvent($lsEventId,2);//den hier nur 2 sekunden
                    else
                        $this->lockEvent($lsEventId);//damit der während der bearbeitungszeit nicht 10234234 mal erneut aufgerufen wird
                    $liK = count($this->iaHandlers);
                    $this->iaHandlers[$liK] = new Thread("handle_event");
                    $this->iaHandlers[$liK]->start($laEvent["type"],$laEvent["id"]);
                    $this->log("Event $lsEventId wurde gestartet","yellow");
                }
            }
            
            //Cronaufträge
            $liMinute = (int)date("i");
            if($liMinute == 0)
            {
                if(!$lbCron)
                {
                    $lbCron = true;
                    ehLog("Starting Crons","cyan");
                    $liK = count($this->iaHandlers);
                    $this->iaHandlers[$liK] = new Thread("do_crons");
                    $this->iaHandlers[$liK]->start();
                }
            }
            else
                $lbCron = false;
            
            $this->cleanUp();
            usleep(10000);
        }
    }
    
    public function listEvents()
    {
        $lodb = gigraDB::db_open();   
        
        $laEvents = array();
        //$lsQuery = "SELECT id,command,coords,prio FROM v_events GROUP BY coords ORDER BY prio, time ASC";
        $lsQuery = "SELECT                                                                              ".
                   "        e1.id                                                                       ".
                   "    ,   e2.command                                                                  ".
                   "FROM                                                                                ".
                   "(                                                                                   ".
                   "    SELECT                                                                          ".
                   "        SUBSTRING_INDEX(                                                            ".
                   "            MIN(                                                                    ".
                   "                    CONCAT(                                                         ".
                   "                        LPAD(`sub`.`start`,20,'0'),                                 ".
                   "                        LPAD(`sub`.`prio`,5,'0'),'%',                               ".
                   "                        LPAD(CONCAT('%',`sub`.`id`),20,'0')                         ".
                   "                    )                                                               ".
                   "             )                                                                      ".
                   "        ,'%',-1) as id, sub.coords as coords FROM v_events sub GROUP BY sub.coords  ".
                   ") as e1                                                                             ".
                   "LEFT JOIN v_events e2                                                               ".
                   "       ON e1.id = e2.id                                                             ".
                   "      AND e1.coords = e2.coords                                                     ";
        
        $lodb->query($lsQuery);
        
        while($laRow = $lodb->fetch("assoc"))
            $laEvents[] = array("type" => $laRow["command"], "id" => $laRow["id"]);
        
        return $laEvents;
    }
    
}

$oHandler = new GigraHandler();
try {
    $oHandler->start();
} catch (Exception $e) {
    echo $e->getMessage();
}

while(1)
{
    if(!$oHandler->isAlive())
    {
        $oHandler->log("Der HauptHandler musste neu gestartet werden(und das nix gut)","red");
        try {
            $oHandler->start();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    sleep(1);
}
