<?php



function getProdItemQueue($coords,$type = "s")
{
    $lodb = gigraDB::db_open();
    
    $lsItems = "";
    $lodb->query("SELECT `{$type}` FROM itemqueue WHERE coords = '$coords' AND `{$type}` > ''");
    
    while($laRow = $lodb->fetch())
    {
        $lsItems = mergeIkf($lsItems,$laRow[0]);
    }
    
    return $lsItems;
}

function read_schiffe($coords,$k=-1,$NOW=-1)  
{
    $lodb = gigraDB::db_open();
    
    $ikf = $lodb->getOne("SELECT s FROM schiffe WHERE coords='$coords'");
    if(!$ikf)
    {
        $lodb->query("INSERT INTO schiffe SET coords = '$coords'");
        $ikf = array("");
    }
    
    $lsFromQueue = getProdItemQueue($coords,"s");
    
    if($lsFromQueue != "")
    {
        $ikf[0] = mergeIkf($ikf[0],$lsFromQueue);
        $lodb->query("UPDATE schiffe SET s = '{$ikf[0]}' WHERE coords = '{$coords}'");
        $lodb->query("DELETE FROM itemqueue WHERE coords = '{$coords}' AND s > ''");
    }
    
    $ikf = ikf2array($ikf[0]);
    ksort($ikf);
    
    return $ikf;
}

function read_vert($coords,$k=-1,$NOW=-1)
{
    $lodb = gigraDB::db_open();
    
    $ikf = $lodb->getOne("SELECT v FROM verteidigung WHERE coords='$coords'");
    if(!$ikf)
    {
        $lodb->query("INSERT INTO verteidigung SET coords = '$coords'");
        $ikf = array("");
    }
    
    $lsFromQueue = getProdItemQueue($coords,"v");
    
    if($lsFromQueue != "")
    {
        $ikf[0] = mergeIkf($ikf[0],$lsFromQueue);
        $lodb->query("UPDATE verteidigung SET v = '{$ikf[0]}' WHERE coords = '{$coords}'");
        $lodb->query("DELETE FROM itemqueue WHERE coords = '{$coords}' AND v > ''");
    }
    
    $ikf = ikf2array($ikf[0]);
    ksort($ikf);

    return $ikf;
}

function add_schiffe($coords,$aaSchiffe,$asTyp = "S")
{
    /*waitUnlock("add_schiffe");
    lock("add_schiffe");
    $lodb = gigraDB::db_open();
    
    
    $laSchiffe = $asTyp == "S" ? read_schiffe($coords) : read_vert($coords);
    //alte updaten
    foreach($laSchiffe as $id => $count)
    {
        if(isset($aaSchiffe[$id]))
        {
            $laSchiffe[$id] += $aaSchiffe[$id];
            unset($aaSchiffe[$id]);
        }
    }
    //nun die schiffe die neu sind
    foreach($aaSchiffe as $id => $count)
        $laSchiffe[$id] = $count;
    
    //schreiben
    $lsTable = $asTyp == "S" ? "schiffe" : "verteidigung";
    $lsField = $asTyp == "S" ? "s" : "v";
    
    $lsArray = array2ikf($laSchiffe);
    
    $lsQuery = "UPDATE $lsTable SET $lsField = '$lsArray' WHERE coords = '{$coords}'";
    
    
    
    $lodb->query($lsQuery);
   
    
    unlock("add_schiffe");*/
    
    $lsCol = $asTyp == "V" ? "v" : "s";
    $lsSchiffe = array2ikf($aaSchiffe);
    gigraDB::db_open()->query("INSERT INTO itemqueue (coords,`{$lsCol}`) VALUES ('$coords','{$lsSchiffe}')");
    
    return array();
}

function add_vert($coords,$aaVert)
{
    return add_schiffe($coords,$aaVert,"V");   
}
?>
