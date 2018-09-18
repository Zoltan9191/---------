<?php
//require_once("simple_html_dom.php");
$need_page = $_POST['UERL'];


$headers = array(
 'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
 'content-type' => 'application/x-www-form-urlencoded',      // text/html; charset=UTF-8
 'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
);


$get_video = post($need_page, array(
 'headers' => array(
  'accept: '.$headers['accept'],
  'content-type: '.$headers['content-type'],
  'user-agent: '.$headers['user-agent']
 ),
 'cookies' => $post_auth['cookies']
));        

//echo $get_video['content'];

$HTMLFB =  htmlspecialchars($get_video['content']); 


//echo $HTMLFB;
//echo "<br>";


$HDVID;
$SDVID;
FindURL();
$Photo;

	function FindURL()
	{
	//	global $GUShtml;
		global $HTMLFB;
		//echo $HTMLFB;



$HD = '/hd\_src\_no\_ratelimit:&quot;(https.+?mp4.*?)&quot;/';

$SD = '/sd\_src\_no\_ratelimit:&quot;(https.+?mp4.*?)&quot;/';


		
		
		
		$rePhoto = '/og:image&quot;.content=&quot;(https.*?\.jpg.*?)&quot;/';
		
	
		preg_match($HD, $HTMLFB, $matchesHD);
		preg_match($SD, $HTMLFB, $matchesSD);
		//echo $matchesHD[1];
		
		preg_match($rePhoto, $HTMLFB, $matchesPhoto);
		//echo $matchesPhoto[1];

		global $Photo;
		$Photo = str_replace("&amp;", "&", $matchesPhoto[1]);
		//echo $Photo;
		
		global	$HDVID;
	
		$HDVID = $matchesHD[1];
		
	
		global $SDVID;
		$SDVID = $matchesSD[1];
		
	}



	
function post($url = null, $params = null, $proxy = null, $proxy_userpwd = null) {
 $ch = curl_init();
// echo  "<br>" . " url= " . $url . "<br>";
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
 
 
 
 if(isset($params['params'])) {
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params['params']);
  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/coo.txt');   //
  curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/coo.txt');  //
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

?>
<link rel="stylesheet" type="text/css" href="FB.css">



<div class="a1-div1">      

<video width="500" height="400" poster="<?echo $Photo;?>"
 controls=""  name="media"><source src="<?echo $SDVID;?>" type="video/mp4" download="NormalQuality" >  </video>
  </div>


<div class="a1-div2">
<?
if($SDVID !=null) {
	
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$SDVID."download='Normal Quality'>Download Low Quality </a>";
	echo "<br>";echo "<br>";
}

if($HDVID !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=".$HDVID."download='Good Quality'>Download High Quality </a>";
	echo "<br>";echo "<br>";
}

?>
