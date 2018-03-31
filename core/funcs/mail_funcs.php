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
 
 
function HTMLMail($asTo, $asSubject, $asText)
{
    global $_ACTCONF;
    
    $lsHeader  = "MIME-Version: 1.0\r\n";
    $lsHeader .= "Content-type: text/html; charset=iso-8859-1\r\n";
 
    $lsFromMail = $_ACTCONF["mailfrom"];
    $lsHeader .= "From: $lsFromMail\r\n";
    $lsHeader .= "Reply-To: $lsFromMail\r\n";
    
    $lsHeader .= "X-Mailer: PHP ". phpversion();
    
    $blSendMail = mail($asTo,$asSubject,$asText,$lsHeader);
    
    
    return $blSendMail;
} 

?>