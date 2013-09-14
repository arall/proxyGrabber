<?php

//Help
if($argv[1]=="--help" || $argv[1]=="-h" || $argv[1]=="help"){
	die("\n    Usage: ".$argv[0]." [output]\n\n");
}

$output = "";
$proxies = getProxies();
if(count($proxies)){
	foreach($proxies as $proxy){
		$output .= $proxy['ip'].":".$proxy['port']."\n";
	}
}

//Write to file
if($argv[1]){
	file_put_contents($argv[1], $output);
//Print output
}else{
	echo $output;
}

function getProxies(){
	$list = array();
	//Post data
	$post = "ac=on&c%5B%5D=United+States&c%5B%5D=Indonesia&c%5B%5D=China&c%5B%5D=Brazil&c%5B%5D=Russian+Federation&c%5B%5D=Iran";
	$post = "&c%5B%5D=Colombia&c%5B%5D=Thailand&c%5B%5D=India&c%5B%5D=Egypt&c%5B%5D=Ukraine&c%5B%5D=Korea%2C+Republic+of";
	$post = "&c%5B%5D=Germany&c%5B%5D=Turkey&c%5B%5D=Poland&c%5B%5D=Argentina&c%5B%5D=Mongolia&c%5B%5D=Peru&c%5B%5D=Latvia";
	$post = "&c%5B%5D=Ecuador&c%5B%5D=Canada&c%5B%5D=South+Africa&c%5B%5D=Taiwan%2C+Republic+of+China&c%5B%5D=France";
	$post = "&c%5B%5D=Venezuela&c%5B%5D=Hong+Kong&c%5B%5D=Spain&c%5B%5D=United+Kingdom&c%5B%5D=Chile&c%5B%5D=Australia";
	$post = "&c%5B%5D=Kazakhstan&c%5B%5D=Italy&c%5B%5D=Czech+Republic&c%5B%5D=Philippines&c%5B%5D=Netherlands&c%5B%5D=Japan";
	$post = "&c%5B%5D=Nigeria&c%5B%5D=Viet+Nam&c%5B%5D=Romania&c%5B%5D=Bulgaria&c%5B%5D=Bangladesh&c%5B%5D=Kenya";
	$post = "&c%5B%5D=Cambodia&c%5B%5D=Malaysia&c%5B%5D=Switzerland&c%5B%5D=Mexico&c%5B%5D=United+Arab+Emirates";
	$post = "&c%5B%5D=Hungary&c%5B%5D=Portugal&c%5B%5D=Albania&c%5B%5D=Lithuania&c%5B%5D=Kuwait&c%5B%5D=Slovakia";
	$post = "&c%5B%5D=Iraq&c%5B%5D=Sri+Lanka&c%5B%5D=Pakistan&c%5B%5D=Serbia&c%5B%5D=Paraguay&c%5B%5D=Bosnia+and+Herzegovina";
	$post = "&c%5B%5D=Singapore&c%5B%5D=Macedonia&c%5B%5D=Malta&c%5B%5D=Saudi+Arabia&c%5B%5D=Denmark&c%5B%5D=Norway";
	$post = "&c%5B%5D=Palestinian+Territory%2C+Occupied&c%5B%5D=Dominican+Republic&c%5B%5D=Costa+Rica&c%5B%5D=Ghana";
	$post = "&c%5B%5D=Mozambique&c%5B%5D=Belgium&c%5B%5D=Gibraltar&c%5B%5D=Lao+PDR&c%5B%5D=Uganda&c%5B%5D=Luxembourg";
	$post = "&c%5B%5D=Cote+D%27Ivoire&c%5B%5D=Benin&c%5B%5D=Puerto+Rico&c%5B%5D=Israel&c%5B%5D=Ireland&c%5B%5D=Austria";
	$post = "&c%5B%5D=Croatia&c%5B%5D=Greece&c%5B%5D=Zimbabwe&c%5B%5D=Brunei+Darussalam&c%5B%5D=Georgia&c%5B%5D=Azerbaijan";
	$post = "&c%5B%5D=Belarus&c%5B%5D=Moldova%2C+Republic+of&p=8080&pr%5B%5D=0&pr%5B%5D=1&a%5B%5D=0&a%5B%5D=1&a%5B%5D=2";
	$post = "&a%5B%5D=3&a%5B%5D=4&pl=on&sp%5B%5D=3&ct%5B%5D=3&s=0&o=0&pp=2&sortBy=date";
	//Curl
	$content = curl("http://www.hidemyass.com/proxy-list/search-227289", $post);
	//Table
	$table = get_between($content, '<table id="listtable"', "</table>");
	if($table){
	    //Rows
	    $trs = get_between($table, "<tr", "</tr>");
	    //Unset thead
	    unset($trs[0]);
	    if(count($trs)){
	        foreach($trs as $tr){
	            //Cols
	            $tds = get_between($tr, "<td", "</td>");
	            if(count($tds)){
	                //Last Update
	                $current['lastUpdate'] = date("Y-m-d H:i:s", @trim(get_between($tr, 'timestamp" rel="', '"')));
	                //Ip
	                    //Style Obfs
	                    $style = get_between($tds[1], "<style>", "</style>");
	                    if($style){
	                        $classes = get_between($style, ".", "{display:none}");
	                        $classes[] = "display:none";
	                    }
	                    //Ip
	                    $current['ip'] = "";
	                    $elements = get_between($tds[1], "<", "</");
	                    if(count($elements)){
		                    foreach($elements as $element){
		                        if(!strstr_array($element, $classes)){
		                            $part = @trim(substr($element, strpos($element, ">")+1));
		                            if($part){
		                                $current['ip'] .= $part;
		                            }
		                        }
		                    }
		                }
	                //Port
	                $current['port'] = @trim(substr($tds[2], strpos($tds[2], ">")+1));
	                //Country
	                $current['country'] = @trim(get_between($tds[3], '/>', '</'));
	                //Speed
	                $current['speed'] = @trim(end(get_between($tds[4], 'class="', '"')));
	                //Conection Time
	                $current['time'] = @trim(end(get_between($tds[5], 'class="', '"')));
	                //Type
	                $current['type'] = @trim(substr($tds[6], strpos($tds[6], ">")+1));
	                //Anonymity
	                $current['anonymity'] = @trim(substr($tds[7], strpos($tds[7], ">")+1));
	                //ignore sheeeeit
	                if($current['ip']){
	                    $list[] = $current;
	                    
	                }
	            }
	        }
	    }
	}
	return $list;
}

/**
 Helpers
*/
function get_between_help($end,$r){
   $r = explode($end,$r);
   return $r[0];   
}
function get_between($content,$start,$end){
   $r = explode($start, $content);
   if (isset($r[1])){
       array_shift($r);
       $end = array_fill(0,count($r),$end);
       $r = array_map('get_between_help',$end,$r);
       if(count($r)>1)
           return $r;
       else
           return $r[0];
   } else {
       return array();
   }
}

function curl($url, $post=""){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)");
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST , true);
	curl_setopt($ch, CURLOPT_POSTFIELDS , $post);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$exec = curl_exec($ch);
	curl_close($ch);
	return $exec;
}

function strstr_array($haystack, $needle){
    if(!is_array($needle)){
        return false;
    }
    foreach($needle as $n){
        if(strstr($haystack, $n)){
            return true;
        }
    }
    return false;
}

?>