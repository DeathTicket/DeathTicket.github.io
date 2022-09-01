<?php
/*
    Created by aqil.almara
*/

date_default_timezone_set('Asia/Jakarta');
// @error_reporting(E_ALL^(E_NOTICE|E_WARNING));

if (PHP_OS=="WINNT") {$winType = TRUE;} else {$winType = FALSE;}
for ($x=0; $x<strlen("RBDIU"); $x++) {$units_clr["RBDIU"[$x]] = "\x1b[{$x}m";}
$units_clr = array_merge($units_clr, ["b"=> "\x1b[0;30m", "o"=> "\x1b[38;5;130m"]);
for ($x=0; $x<8; $x++) {
    $units_clr[(string)$x] = "\x1b[3{$x}m";
    $units_clr[".bg{$x}"] = "\x1b[0;2;6;30;4{$x}m";
    $units_clr[".ebg{$x}"] = "\x1b[0;1;3{$x}m";
}
$devices = ["Linux; Android 10; CPH2161", "Linux; Android 10; CPH2179", "Linux; Android 10; H8324", "Linux; Android 10; Infinix X657", "Linux; Android 10; ONEPLUS A5010", "Linux; Android 10; ONEPLUS A6010", "Linux; Android 10; SM-A207F", "Linux; Android 10; SM-A600FN", "Linux; Android 10; SM-G960F", "Linux; Android 10; SM-G960W", "Linux; Android 10; SM-G973W", "Linux; Android 10; SM-M215F", "Linux; Android 10; SM-N975F", "Linux; Android 10; vivo 1920", "Linux; Android 11; M2007J3SG", "Linux; Android 11; Mi Note 10 Lite", "Linux; Android 11; Pixel 3", "Linux; Android 11; Pixel 5", "Linux; Android 11; SM-F700U", "Linux; Android 11; SM-G9700", "Linux; Android 11; SM-G9750", "Linux; Android 11; SM-G977B", "Linux; Android 11; SM-G988B", "Linux; Android 11; SM-G991B", "Linux; Android 11; SM-G998U", "Linux; Android 11; SM-N975F", "Linux; Android 11; SM-N980F", "Linux; Android 11; SM-N981U1", "Linux; Android 11; V2030", "Linux; Android 9; CPH2015", "Linux; Android 9; CPH2083", "Linux; Android 9; LG-H932", "Linux; Android 9; LM-G710VM", "Linux; Android 9; LM-X420", "Linux; Android 9; Redmi 7", "Linux; Android 9; SAMSUNG SM-A530F", "Linux; Android 9; SM-A102U", "Linux; Android 9; SM-A205W", "Linux; Android 9; SM-G950U1", "Linux; Android 9; SM-J737T1", "Linux; Android 9; SM-N950N", "Linux; Android 9; SM-S767VL"];
$versions = ["77.0.3865.116", "80.0.3987.149", "83.0.4103.101", "83.0.4103.106", "83.0.4103.96", "84.0.4147.125", "85.0.4183.101", "85.0.4183.127", "85.0.4183.81", "86.0.4240.114", "86.0.4240.185", "87.0.4280.101", "87.0.4280.66", "88.0.4324.152", "88.0.4324.181", "88.0.4324.93", "89.0.4389.105", "89.0.4389.86", "89.0.4389.90", "90.0.4430.19", "90.0.4430.66", "92.0.4515.159", "92.0.4515.166", "93.0.4577.62", "94.0.4606.61", "94.0.4606.71", "94.0.4606.85", "95.0.4638.74", "96.0.4664.45", "103.0.0.0"];
$proxy = 'scraperapi:04c351195f065ca5d0d1c0ebbb88d516@proxy-server.scraperapi.com:8001';


function clr($value, $start="?R?B", $end="?R") {
    global $winType, $units_clr;
    $value = "{$start}{$value}{$end}";
    if ($winType):
        foreach ($units_clr as $kc => $vc) {$value = str_replace("?{$kc}", "", $value);}
        $value = str_replace("?n", "<br >", $value);
    else:
        foreach ($units_clr as $kc => $vc) {$value = str_replace("?{$kc}", $vc, $value);}
        $value = str_replace("?n", "\n", $value);
    endif; return($value);
}
function printn($value="", $end="?n", $start="\r") {
    if (is_array($value)):
        $value = json_encode($value, JSON_PRETTY_PRINT);
        if ($end=="?n") $end = "";
        return(print(clr("\r{$end}{$value}?n")));
    elseif (is_array(json_decode($value, 1))):
        return(printn(json_decode($value, 1), $end, $start));
    endif; if ($start=="\r") {cll();}
    return(print(clr("{$start}{$value}{$end}")));
}
function cll() {print("\r".str_repeat(" ", 59)."\r");}
function console_print($value, $clr=2, $infotype="time") {
    global $winType;
    if (is_array($value)):
        $value = json_encode($value, JSON_PRETTY_PRINT);
    elseif (is_array(json_decode($value, 1))):
        return(console_print(json_decode($value, 1), $clr, $infotype));
    endif;
    if ($infotype=="time") {$infotype = date("H:i:s");}
    if ($winType) {$space = "";} else {$space = " ";}
    printn("?.bg{$clr}{$space}{$infotype} ?.ebg{$clr}{$space}{$value}");
}
function delay($timer=0, $f="i:s", $msg="Please Wait") {
    while (time() <= $timer):
        $sec = $timer-time();
        printn(" ?6》?2{$msg} ?0⟨?7".gmdate($f, ceil($sec))."?0⟩ ?2seconds ☕🚬  ?0", "");
        usleep(1E+6);cll();
    endwhile;
}
function reconnect($sec=10, $f="s", $msg="Reconnecting in") {delay((time()+$sec), $f, $msg);}

function user_agent() {
    global $devices, $versions;
    list($device, $version) = [$devices[array_rand($devices)], $versions[array_rand($versions)]];
    return("Mozilla/5.0 ({$device}) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$version} Mobile Safari/537.36");
}
function curl($endpoint, $headers=array(), $post=NULL, $proxy=NULL, $fname_cookie=NULL, $msg="Loading") {
    printn(" ?6》?2{$msg}...  ?0", "");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_VERBOSE, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    if ($proxy) {curl_setopt($curl, CURLOPT_PROXY, $proxy);}
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    if (!empty($post)):
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, trim($post));
    endif;
    if ($fname_cookie) {
        curl_setopt($curl, CURLOPT_COOKIEJAR, $fname_cookie);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $fname_cookie);
    }
    $value = curl_exec($curl);curl_close($curl);cll();
    $http_code = curl_getinfo($curl)["http_code"];
    if ($http_code!=200) {return($http_code);}
    if (is_array(json_decode($value, 1))):
        $value = json_decode($value, 1);
    endif; return($value??"");
}
function pars($p1, $p2, $value, $n=1) {return(explode($p2??"", explode($p1??"", $value)[$n]??"")[0]??"");}


function view_ajax($post_id, $title=NULL, $server="piscans.com") {
    global $_useragent, $chap;
    $request = http_build_query([
        "action"  => "dynamic_view_ajax",
        "post_id" => $post_id
    ]);
    $headers = array(
        "Host:".$server,
        "content-length:".strlen($request),
        "user-agent:".$_useragent,
        "content-type:application/x-www-form-urlencoded; charset=UTF-8",
        "x-requested-with:XMLHttpRequest",
        "accept-language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
    );
    while (TRUE) {
        $resp = curl("https://{$server}/wp-admin/admin-ajax.php", $headers, $request);
//        printn($resp);

//        $west = pars("west=+(", ")\n", $resp);
//        preg_match_all("/\((.*)]\)/", $west, $west);
//        $east = pars("east=+", "\n", $resp);
//        printn($west);
//        printn($east);
//        exit();

        if (is_array($resp)) {break;}
        reconnect();
    }
    if ($title) {console_print("?oTitle ?2{$title}");}
    console_print(str_replace(" Views", "", "?2Views: ?7{$resp['views']}?0|?2Series: ?7{$resp['series']}?0|?2Chapter: ?7{$chap}"));
    sleep(1);return($resp['views']);
}
function loadReactions($thread) {
    global $_useragent, $result, $frs;

    $req = http_build_query([
        "thread" => $thread,
        "api_key" => "E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F"
    ]);
    $headers = array(
        "Host:disqus.com",
        "Connection:keep-alive",
        "X-Requested-With:XMLHttpRequest",
        "User-Agent:".$_useragent,
        "Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
    );
    while (TRUE) {
        $resp = curl("https://disqus.com/api/3.0/threadReactions/loadReactions?".$req, $headers);
        if (is_array($resp)) {$reactions = $resp["response"]["reactions"];break;}reconnect();
    }
    for ($x=0; $x<count($reactions); $x++) {
        $result["Responses"] += $reactions[$x]["votes"];
        $result[$reactions[$x]["text"]] = str_pad($reactions[$x]["votes"], 1, " ", 0);
    }
    if (!$frs) {$frs = $result["Responses"];}
    console_print("?7{$result['Responses']} ?2Responses\r\n           ?2Upvote?7{$result['Upvote']}?0|?2Funny?7{$result['Funny']}?0|?2Love?7{$result['Love']}?0|?2Surprised?7{$result['Surprised']}?0|?2Angry?7{$result['Angry']}?0|?2Sad?7{$result['Sad']}");
    sleep(1);
}
function vote($thread, $reaction) {
    global $_useragent, $proxy, $result, $countVote, $fra, $cvt, $frs;

    $request = http_build_query([
        "thread" => $thread,
        "reaction" => $reaction,
        "api_key" => "E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F"
    ]);
    $headers = array(
        "Host:disqus.com",
        "Connection:keep-alive",
        "Content-Length:".strlen($request),
        "X-Requested-With:XMLHttpRequest",
        "User-Agent:".$_useragent,
        "Content-Type:application/x-www-form-urlencoded; charset=UTF-8",
        "Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
    );
    while (TRUE) {
        $resp = curl("https://disqus.com/api/3.0/threadReactions/vote", $headers, $request, $proxy, false);$cvt++;
        if (is_array($resp)) {if (!$fra) {$fra = $result[$resp["response"]["text"]];$cvt += $fra;} break;}reconnect();
    }
    $countVote++;
    console_print($resp["response"]["text"]."++ ?0({$fra} + {$countVote} = ".($fra+$countVote)."/{$cvt}|{$frs})");
}
function clickAD($_var) {
    global $_useragent;
    $headers = array(
        "Host:vexacion.com",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:".$_useragent,
        "Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
    );
    $ymid = rand(pow(10,17), pow(10,18)-1)."".rand(0, 9);
    for ($i=0; $i<2; $i++):
        while (TRUE):
            $resp = curl("http://vexacion.com/afu.php?zoneid=1320852&var={$_var}&ymid={$ymid}", $headers);
            $sub_id1 = pars("sub_id1=", "&", $resp);
            if ($sub_id1) {break;}
            $sub_id1 = pars("//", "/", $resp, 2);
            if ($sub_id1) {break;}
            if ($resp=="204") {$sub_id1 = "NULL";break;}

            file_put_contents("manhwalist-code.txt", $resp);
            reconnect();
        endwhile;
        console_print("ymid?1:?7{$ymid}?0|?2_ad?1:?7{$sub_id1}");
        delay(time()+rand(3, 5), "s");$ymid-=3;
        if ($ymid<1E+18) {$ymid+=6;}
    endfor;
    sleep(1);
}



//if ($winType) {@system("cls");} else {@system("clear");}
printn("?0".str_repeat("━", 59));

$dtb_toon = [
    "ticket-hero" => [
        ["18672", "9299998845", 106],
        ["19067", "9303700924", 107],
        ["19085", "9305294039", 108],
        ["19118", "9305785466", 109],
        ["19127", "9306697813", 110],
        ["19268", "9308501063", 111],
        ["19385", "9308967438", 112],
        ["19686", "9310450117", 113],
        ["19698", "9311384254", 114],
        ["20601", "9313235557", 115],
        ["20642", "9316044298", 116],
        ["20716", "9316656931", 117],
        ["20719", "9317419458", 118],
        ["21667", "9330256458", 119],
        ["21686", "9330773190", 120],
        ["", "", 121],
        ["", "", 122],
    ],
    "reality-quest" => [
        ["21698", "9331768518", 49],
    ]
];
$reactions = [
    "piscans" => ["Upvote"=>"1636430","Funny"=>"1636431","Love"=>"1636432","Surprised"=>"1636433","angry"=>"1636434","Sad"=>"1636435"],
    "manhwalist" => ["Upvote"=>"1522048","Funny"=>"1522049","Love"=>"1522050","Surprised"=>"1522051","angry"=>"1522052","Sad"=>"1522053"]
];


$rn = 0;
$web = "piscans";
$reaction = "Upvote";
$title = "ticket-hero";
 $title = "reality-quest";

list($countVote, $cvt, $frs, $fra) = [0, 0, 0, 0];
list($post_id, $thread, $chap) = $dtb_toon[$title][$rn];
$_var = ["manhwalist" => 939661, "piscans" => 964710][$web];
$_limit = NULL;


while (TRUE):
    $result = array("Responses" => 0);
    $_useragent = user_agent();

    view_ajax($post_id, $title, "{$web}.com");
    clickAD($_var); loadReactions($thread);
    vote($thread, $reactions[$web][$reaction]);

    printn("?0".str_repeat("━", 59));
    if (!$_limit) {
        for ($i=600; $i>0; $i-=200) {
            if ($result["Responses"]<$i) {$_limit=$i;}
        }
    }
    if ($result["Responses"]>=$_limit) {exit();}
    delay(time()+rand(60, 300));
endwhile;
?>
