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

class gigraDB
{
	private $con;
	
	private $result;
	
	public function __construct($con = FALSE)
	{
		/*if(!$con == false)
			$this->con = $con;
		else*/ 
			$this->connect();
	}
	public function connect()
	{
		global $_CONFIG;// global $_dbopen;
		global $_RUNDE;
	    global $_SESSION;
	    
	    $cfg = $_CONFIG[$_RUNDE]["mysql"];
	    //Warum mysqli_pconnect()?
	    //mysqli_pconnect oeffnet eine persistente Verbindung zur MySQL-DB
	    //es muss nicht jedesmal neu connectet werden
	    //Das spart (bei mir) 600 (!) ms
        if(!defined("HANDLER_MODE"))
	        $this->con = mysqli_connect($cfg[0],$cfg[1],$cfg[2]);
        else
            $this->con = mysqli_connect($cfg[0],$cfg[1],$cfg[2]);
//            $this->con = mysqli_connect("p:".$cfg[0],$cfg[1],$cfg[2]);
        
	    if(!$this->con)
	    {
            if(!defined("HANDLER_MODE") || !isset($_GET['show_db_error']))
                redirect("/dberror.php");
            else
	    	    die("Connect Error: ".mysqli_connect_error());
	    }
	    $dbsel = mysqli_select_db($this->con,$cfg[3]);
        
        $liAttempts = 1;
        while(!$dbsel)
        {
            if(!defined("HANDLER_MODE"))
                 redirect("/dberror.php");
            else
            { 
                $liAttempts++;
                if($liAttempts > 20)
                    die("Something is wrong with the Server, we failed after 20 attempts of reselct DB");
                $liErrno = mysqli_errno($this->con);
                if($liErrno == 2013 or $liErrno == 2006 or $liErrno == 2002)
                {
                    $this->con = mysqli_connect("p:".$cfg[0],$cfg[1],$cfg[2]);
                    $dbsel = mysqli_select_db($this->con,$cfg[3]);
                }
                else
                    die("cannot select database '{$cfg[3]}'".PHP_EOL.mysqli_error($this->con));
            }
	    }
        mysqli_set_charset($this->con, "utf-8");
	}
	public function query($qry,$cache = false)
	{
        if(!defined("HANDLER_MODE") && isAdmin())
        {
            if(!isset($_SESSION["timeprofiles"]))
                $_SESSION["timeprofiles"] = array();
            if(!isset($_SESSION["donequery"]))
                $_SESSION["donequery"] = array();
            if(!isset($_SESSION["timeprofiles"]["querycount"]))
                $_SESSION["timeprofiles"]["querycount"] = 1;
            else
                $_SESSION["timeprofiles"]["querycount"]++;
                
            //backtrace
            $bt = debug_backtrace();
            $bt = array_reverse($bt);
            $lsBt = "";
            foreach($bt as $bt2)
                $lsBt .= "<br>--".array_pop(explode("/",$bt2["file"]))."::".$bt2["function"];
            
            $_SESSION["donequery"][] = $qry."($lsBt)<br><br>";
        }
        $liAttempts = 1;
		$this->result = mysqli_query($this->con,$qry);//
        while(!$this->result)
        {
            $liErrno = mysqli_errno($this->con);
            if($liAttempts > 20)
                die("Something is wrong with the Server, we failed after 20 attempts of reconnect");
            if($liErrno == 2013 or $liErrno == 2006 or $liErrno == 2002)
            {
                $this->connect();
                $this->result = mysqli_query($this->con,$qry);
            }
            else
                die("MySQLError:".mysqli_error($this->con) . " in ". $qry);
            $liAttempts++;
        }
		return $this->result;
	}
	public function numrows()
	{
		return mysqli_num_rows($this->result);
	}
    public function affectedRows()
    {
        return mysqli_affected_rows($this->con);   
    }
	public function fetch($type = "array")
	{
		switch ($type)
		{
			case "array":
			default:
				return mysqli_fetch_array($this->result);
			case "assoc":
				return mysqli_fetch_assoc($this->result);
			case "row":
				return mysqli_fetch_row($this->result);
		}
	}
	public function getOne($qry,$cache = 0,$cacheName = '')
	{
        $cache = 0;
        if($cache > 0)
        {
            $lsCacheName = $cacheName == '' ? md5($qry) : $cacheName;
            $loMC = new gigraMC($cache);
            $data = $loMC->getData($lsCacheName);
            if($data == null)
            {
                $this->query($qry);
                $data = $this->fetch("array");
                $loMC->setData($lsCacheName,$data);
            }
            
            return $data;
        }
        else
		{
    	    $this->query($qry);
            return $this->fetch("array");
		}
	}
    
    
    public function escape($asString)
    {
        return mysqli_real_escape_string($this->con,$asString);   
    }
    
    public function inserId()
    {
        return mysqli_insert_id($this->con);   
    }
	/**
	 * 
	 * Opens connection to DB
	 * @param unknown_type $con
	 * @return gigraDB
	 */
	public static function db_open($con = FALSE)
	{
		return new gigraDB($con);
	}
	public function __destruct()
	{
        return;
		if($this->result)
			@mysqli_free_result($this->result);
		@mysqli_close($this->con);
	}
}