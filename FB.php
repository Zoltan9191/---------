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
 
////////////////////////////////////////////
// $html = new simple_html_dom();
// $html->load($result);
// $html = file_get_html($result);
 //$kek = file_get_html($html);
 
// echo " html= " . $html . " =html ";
// $div_video=$html->find('video',0);
 //echo " vid= " . $div_video . " =vid ";

 //foreach($html->find('video') as $content) 
   //    echo $content->http . '<br>';
	   
//	   foreach ($html->find('<div class="videoplayer_media">') as $content)
  // echo $content . '<br>';
 ///////////////////////////////////////////
 
 $result_explode = explode("\r\n\r\n", $result);
 
 $headers = ((isset($result_explode[0])) ? $result_explode[0]."\r\n" : '').''.((isset($result_explode[1])) ? $result_explode[1] : '');
 $content = $result_explode[count($result_explode) - 1];
 
 preg_match_all('|Set-Cookie: (.*);|U', $headers, $parse_cookies);
 
 $cookies = implode(';', $parse_cookies[1]);
 
 curl_close($ch);
 
 return array('headers' => $headers, 'cookies' => $cookies, 'content' => $content);
}


// https://video-amt2-1.xx.fbcdn.net/v/t42.1790-2/16773485_618389698362124_8040896282463567872_n.mp4?efg=eyJybHIiOjM4OCwicmxhIjo1ODEsInZlbmNvZGVfdGFnIjoic3ZlX3NkIn0%3D&rl=388&vabr=216&oh=6bde75a7a9e0c766b8970c5610f0cadd&oe=593DA164            norm 
// https://video-amt2-1.xx.fbcdn.net/v/t43.1792-2/16462684_746230602193750_6776466143973474304_n.mp4?efg=eyJybHIiOjE3MDEsInJsYSI6MzA1MiwidmVuY29kZV90YWciOiJzdmVfaGQifQ%3D%3D&rl=1701&vabr=1134&oh=95237488bbf17411763ab1497ce54951&oe=593DBC33    hd


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