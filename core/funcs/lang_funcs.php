<?php
function myrev($str)
{
    $inHTML = false;
    $HTMLText = "";
    $newStr = "";
    for($i=strlen($str)-1;$i>=0;$i--)
    {
        $char = $str[$i];
        if($char == ">")
        {
            $inHTML = true;
        }
        
        
        if($inHTML === true)
            $HTMLText .= $char;
        else
            $newStr .= strflip($char);
            
        if($char == "<")
        {
            $inHTML = false;
            $newStr .= strrev($HTMLText);
            $HTMLText = "";
        }
    }
    
    return $newStr;
}
function strflip($text)
{
  $text=strtolower($text);

$arr = array(
"a" => "&#x250;",
"b" => "q",
"c" => "&#x254;",
"d" => "p",
"e" => "&#x01DD;",
"f" => "&#x25F;",
"g" => "&#x387;",
"h" => "&#x0265;",
"i" => "&#x0131;",
"j" => "&#x027E;",
"k" => "&#x029E;",
"l" => "&#x0283;",
"m" => "&#x026F;",
"n" => "u",
"p" => "d",
"q" => "b",
"r" => "&#x0279;",
"t" => "&#x0287;",
"u" => "n",
"v" => "&#x028C;",
"w" => "&#x028D;",
"y" => "&#x028E;",
"B" => "D"
);
$result = strtr($text,$arr);
return $result;
}

function l()
{
	global $_LANG;
	$laArgs = func_get_args();
	if(count($laArgs) == 1)
	{
		$asKey = array_shift($laArgs);
		return ($_LANG[$asKey]);
	}
	else 
	{
		$laArgs[0] = $_LANG[$laArgs[0]];
		return (call_user_func_array("sprintf", $laArgs));
	}
}
function getLangFull()
{
    global $_LANG;
    
    return $_LANG;
}
?>
