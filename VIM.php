<?php


//<video preload="" src="https://07-lvl3-pdl.vimeocdn.com/01/3590/0/17950038/35405750.mp4?expires=1499942663&amp;token=091a1dfd26f7c925657d4"></video>
// xpath //*[@id="17950038"]/div/div[1]/div/div/video


require_once("simple_html_dom.php");


$need_page = $_POST['UERL'];

if (isset($need_page)) {

$c = curl_init($need_page);    //brauzer emulator
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($c);

$html = file_get_html($need_page);

//echo htmlspecialchars($html);

//echo $html;

$reConf = '/config_url\"\:\"(.*?https\:.*?config\?.*?)\"/';
preg_match_all($reConf, $html, $matches, PREG_SET_ORDER, 0);

//print_r($matches);
//echo urldecode($matches[0][1]);                               //link with ideos - replace
//echo "<br>";
//echo $Conf = str_replace("\\","",$matches[0][1]);             //link with videos + replace
$Conf = str_replace("\\","",$matches[0][1]);    
//echo "<br>";
$Conftext = file_get_contents($Conf);


$reConftext = '/\,\"url\"\:\"(https.*?)\".*?quality\"\:\"(\d+p)\"/';
preg_match_all($reConftext, $Conftext, $Confmatch, PREG_SET_ORDER, 0);

//print_r($Confmatch);
/*
echo $Confmatch[0][1] . " Quality: " . $Confmatch[0][2] . "<br>";   //270p
echo $Confmatch[1][1] . " Quality: " . $Confmatch[1][2] . "<br>";   //720p
echo $Confmatch[2][1] . " Quality: " . $Confmatch[2][2] . "<br>";   //360p
*/

// for match all photo to video
//  $re = '/thumbs\"\:\{\"\d+\"\:\"(https.*?)\"\,\"\d+\"\:\"(https.*?)\"\,\"\d+\"\:\"https.*?\"/';
$rePhoto = '/base\"\:\"(https.+?)\"/';
preg_match_all($rePhoto, $Conftext, $Photo, PREG_SET_ORDER, 0);
//print_r($Photo);
//echo $Photo[0][1];


$headers = array(
'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
'Accept-Encoding' => 'gzip, deflate, br',
'Accept-Language' =>'en-US,en;q=0.8',
'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36'
);

$headers222 = array(
 'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
 'content-type' => 'application/x-www-form-urlencoded',
 'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36'
);
 

function post($url = null, $params = null, $proxy = null, $proxy_userpwd = null) {
 $ch = curl_init();
 //echo " url= " . $url . " =url ";
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
 curl_setopt($ch, CURLOPT_REFERER,1);
 
 if(isset($params['params'])) {
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params['params']);
 }
 
 if(isset($params['headers'])) {
  curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);
 }
 
 if(isset($params['cookies'])) {
  curl_setopt($ch, CURLOPT_COOKIE, $params['cookies']);
 }
 
 if($proxy) {
  curl_setopt($ch, CURLOPT_PROXY, $proxy);
 
  if($proxy_userpwd) {
   curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_userpwd);
  }
 }
 
 $result = curl_exec($ch);
 
 
 $result_explode = explode("\r\n\r\n", $result);
 
 $headers = ((isset($result_explode[0])) ? $result_explode[0]."\r\n" : '').''.((isset($result_explode[1])) ? $result_explode[1] : '');
 $content = $result_explode[count($result_explode) - 1];
 
 preg_match_all('|Set-Cookie: (.*);|U', $headers, $parse_cookies);
 
 $cookies = implode(';', $parse_cookies[1]);
 
 curl_close($ch);
 
 return array('headers' => $headers, 'cookies' => $cookies, 'content' => $content);
}

}


?>

<link rel="stylesheet" type="text/css" href="FB.css">

<div class="a1-div1">      

<video width="500" height="400" poster="<?echo $Photo[0][1];?>"
 controls=""  name="media"><source src="<?echo $Confmatch[0][1];?>" type="video/mp4" download="720p Quality" >  </video>
  </div>

<div class="a1-div2">

<?//if($Confmatch[0][2] !=null) {echo " a align='center' id='a1-div-a' class='text-center' href="; echo $Confmatch[0][1] ."download=''";echo "Downlod" . $Confmatch[0][2]  ; } 

if($Confmatch[0][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[0][1]."download=''>Download ".$Confmatch[0][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[1][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[1][1]."download=''>Download ".$Confmatch[1][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[2][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[2][1]."download=''>Download ".$Confmatch[2][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[3][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[3][1]."download=''>Download ".$Confmatch[3][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[4][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[4][1]."download=''>Download ".$Confmatch[4][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[5][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[5][1]."download=''>Download ".$Confmatch[5][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[6][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[6][1]."download=''>Download ".$Confmatch[6][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[7][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[7][1]."download=''>Download ".$Confmatch[7][2]."</a>";
	echo "<br>";echo "<br>";
}
if($Confmatch[8][2] !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$Confmatch[8][1]."download=''>Download ".$Confmatch[8][2]."</a>";
	echo "<br>";echo "<br>";
}

?>  


</div>






