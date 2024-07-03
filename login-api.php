<?php
require_once(__DIR__ . '/../config.php');

function get_user_by_username($username) {
    global $DB;
    return $DB->get_record('user', array('username' => $username, 'deleted' => 0));
}
function hex2asc($myhex){
    $valHex = $myhex ;
    $ValDec="A";
    $valLen = strlen($valHex);
    $valLen2= strlen($valHex);
    $intPof = $valLen;
    $num=1;
    for ($i = 0; $i<$valLen; $i++ ){

        $ValDec = substr(substr($valHex,$i,1),0,$i+1);
        $myVal=$ValDec;
        if ($myVal=='A') {$myVal = 10;}
        if ($myVal=='B') {$myVal = 11;}
        if ($myVal=='C') {$myVal = 12;}
        if ($myVal=='D') {$myVal = 13;}
        if ($myVal=='E') {$myVal = 14;}
        if ($myVal=='F') {$myVal = 15;}
        if ($valLen2>= 3){
            $rslt=$myVal*16^($intPof-$i);
            $valLen2=$valLen2-1;
        }elseif ($valLen2==2) {
            $rslt=$myVal*16;
            $valLen2=$valLen2-1;
        }elseif ($valLen2==1) {
            $rslt=$myVal;
        }
        $myRslt = (int)$myRslt + (int)$rslt;
        $num=$num+1;

    }
    
    return chr($myRslt);
}

//-------------------------------------------
function DecryptByKey($str,$fkey){
    $key=$fkey;
    $keylen = strlen($key) ;   
    $sStr = "";
    $j=1;$ix=1;$ii=0;
    for ($i = 0; $i<strlen($str); $i+=2 ){
        $ii=$i+1;
        $hex1="0x".substr($str, $i, 2);
        if ($ii>1){$j=$ii-$j;} 
        $keymod = ($j % $keylen) + 1;
        $hex2="0x".dechex(ord(substr($key, $keymod-1, 1)))	;
        $hexop=hexdec($hex1)-hexdec($hex2);
        $xhexop=dechex($hexop);
        $xhex2asc=hex2asc(strtoupper($xhexop));
        $xstring=(string)($xhex2asc);
        $sStr = $sStr . (string)($xhex2asc);
        $ix=$ix+1;
    }
    return $sStr;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['encrypted'])) {

        // Letak key disini
        $fkey = "<ENTER-YOUR-KEY-HERE>";

        $str = $_GET['encrypted'];
        $course_after_login = isset($_GET['course_after_login']) ? $_GET['course_after_login'] : null;

        $username = DecryptByKey($str, $fkey);

        if ($userauth = get_user_by_username($username)) {
            complete_user_login($userauth);
            if ($course_after_login) {
                redirect($course_after_login);
            } else {
                redirect($CFG->wwwroot . '/my/');
            }
        } else {
            echo "Username not found.";
        }
    } else {
        echo "Username or key parameter not provided.";
    }
} else {
    echo "Invalid request method.";
}
?>