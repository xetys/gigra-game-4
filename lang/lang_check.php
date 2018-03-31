<?


//hauptsprache
include "de.lang.php";


$_ORIG = $_LANG;


//Vergleichssprache
include "en.lang.php";


foreach($_ORIG as $key => $val)
    if(!isset($_LANG[$key]))
        echo "missing key $key<br>".PHP_EOL;