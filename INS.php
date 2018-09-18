<?
require_once("simple_html_dom.php");

$need_page = $_POST['UERL'];



$headers = array(
 'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
 'content-type' => 'text/html',      // text/html; charset=UTF-8  //application/x-www-form-urlencoded
 'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'
);

$get_video = post($need_page, array(
 'headers' => array(
  'accept: '.$headers['accept'],
  'content-type: '.$headers['content-type'],
  'user-agent: '.$headers['user-agent']
 ),
 'cookies' => $post_auth['cookies']
));        

$HTMLINS =  htmlspecialchars($get_video['content']); 



$preg_url = '/og\:video\".content=\"(https:\/\/.*?\.mp4)/';

preg_match_all($preg_url, $get_video['content'], $InsUrl, PREG_SET_ORDER, 0);

//echo $HTMLINS;
//print_r($HTMLINS);

//echo $InsUrl[0][1];
$INSurl = $InsUrl[0][1];
//print_r($InsUrl);

$preg_image = '/og:image".content=\"(https:\/\/.*?\.jpg)/';

preg_match($preg_image, $HTMLINS, $InsImage);

$INSimage=$InsImage[0][1];

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
<html>
<link rel="stylesheet" type="text/css" href="FB.css">



<div class="a1-div1">      

<video width="500" height="400" poster="<? echo $INSimage; ?>"
 controls=""  name="media"><source src="<? echo $INSurl; ?>" type="video/mp4">  </video>
  </div>

 <div class="a1-div2">
<?

if($INSurl !=null) {
	
	echo "<a align='center' id='a1-div-a' class='text-center' href=". $INSurl ." download=''> Download "."</a>";
	echo "<br>";echo "<br>";
}
?>

</div>
</html>
