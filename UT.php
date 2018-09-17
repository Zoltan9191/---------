<?php

require_once("simple_html_dom.php");

$need_page = $_POST['UERL'];

if (isset($need_page)) {
//echo "NP[ " . $need_page . " ]NP"; 

//////////////////////////////////////////////////////////////////////////////////
/*
 $ip = $_SERVER['REMOTE_ADDR'];
 
 function getIpAdress($opion = '', $ban = false)
    {
        if(empty($opion) || $opion == 'REMOTE_ADDR' || is_array($opion))
            $header = 'REMOTE_ADDR';
        
        $ip = @$_SERVER[$header];
     
        if(is_array($opion) && in_array($ip, $opion))
            return $ban;
       
        return $ip;        
    }

$ip = getIpAdress();
//echo "IP: " . $ip;
*/


// Для справочной информации получить значение из любой переменной окружения:
// Для справки можем получить допустим цепочку прокси
//    $ips = getIpAdress('HTTP_X_FORWARDED_FOR');
// Или еще чего-нибудь, допустим:
//    $ip = getIpAdress('HTTP_X_REAL_IP');

//Отфильтровать неугодные IP-адреса
// Массив забаненных IP
  /*  $ban_ips = array('127.0.0.1');
    
    if(!getIpAdress($ban_ips))
        echo 'БАН!';               */

	
//или наоборот, разрешить доступ доверенным:
// Массив доверенных IP
 //   $trasted_ips = array('127.0.0.1');
// Вторым аргументом то, чего ждем на выходе    
//    if(getIpAdress($trasted_ips, true))
//        echo 'Добро пожаловать!';


///////////////////////////////////////////////////////////////////////////////////////




$c = curl_init($need_page);    //brauzer emulator
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($c);

$html = file_get_contents($need_page);



// preg Quality(signature) (if no have signature)//
$Quality1080 = '/quality_label=1080p.*?\\\\u....s=(.*?\..*?)\\\\/';
////////$$$$$$$Quality720 = '/quality_label=720p.*?\\\\u....s=(.*?\..*?)\\\\/';
$Quality480 = '/quality_label=480p.*?\\\\u....s=(.*?\..*?)\\\\/';
$Quality360 = '/quality_label=360p.*?\\\\u....s=(.*?\..*?)\\\\/';
$Quality240 = '/quality_label=240p.*?\\\\u....s=(.*?\..*?)\\\\/';

$Quality720    =  '/url_encoded.*?url=(https.+?)\\\\u.*?s=(.*?\..*?)\\\\.*?itag=22/s';      ///new one
//$Quality720_2  =  '/url_encoded.+?itag=22\\\\u.+?s=(.*?\..+?)\\\\.+?url=(https.+?)\\\\/';             // \,
 $Quality720_1 = '/"url_encoded_fmt_stream_map\".+?(https.+?[^\\\\\,]*)/';
 $Quality720_2 = '/\"url_encoded_fmt_stream_map\".+?s=(\w+\.\w+).+?,/s';

                 
// http itag non-dash (+ sig if have) //
//$itag_3GP_144 = '/itag=17.*?(https.*?)\,itag=/';        // not do
//$itag_3GP_240 = '/itag=36.*?(https.*?)\,itag=/';        // not do
//$itag_nondash_MP4_360 = '/itag=18.*?(https.*?)\,itag=/';   
//$itag_nondash_MP4_360 = '/\,.+?s=(\w+\..+?)\\\\.+?(https.+?)\\\\.+?itag=18.+?\,|\,.+?itag=18.+?(https[^\\\\\,]+).+?s=(\w+.+?)\\\\/';
$itag_nondash_MP4_360 = '/\,[^\,]*itag=18.*?\,/';   $itag_nondash_MP4_360_https = '/(https.*?[^\,\\\\]*)/';  $itag_nondash_MP4_360_sig = '/s=(\w+\.\w+)/';      //'/\w{40}\.\w{40}/';   '/s=(\w+\.\w+)/';                        
//$itag_nondash_MP4_720 = '/itag=22.*?(https.*?)\,itag=/';     // already
//$itag_nondash_Webm_360 = '/itag=43.*?(https.*?)\,itag=/';    // not do
$itag_nondash_Webm_360 = '/\,[^\,]*itag=43.*?\,/'; 


// preg Quality(signature) (if no have signature) //
//preg_match($Quality1080, $html, $SigVideo1080);
//preg_match($Quality720, $html, $SigVideo720);
preg_match($Quality720_1, $html, $SigVideo720_1);    //////////////
preg_match($Quality720_2, $html, $SigVideo720_2);
//preg_match($Quality480, $html, $SigVideo480);
//preg_match($Quality360, $html, $SigVideo360);
//preg_match($Quality240, $html, $SigVideo240);

// preg non-dash itag (+ sig if have) //

//preg_match($itag_3GP_144, $html, $i_3GP_144);
//preg_match($itag_3GP_240, $html, $i_3GP_240);

preg_match($itag_nondash_MP4_360, $html, $i_nd_MP4_360);   preg_match($itag_nondash_MP4_360_https, $i_nd_MP4_360[0], $i_nd_MP4_360_https);       preg_match($itag_nondash_MP4_360_sig, $i_nd_MP4_360[0], $i_nd_MP4_360_sig);
//preg_match($itag_nondash_MP4_720, $html, $i_nd_MP4_720);
preg_match($itag_nondash_Webm_360, $html, $i_nd_Webm_360);  preg_match($itag_nondash_MP4_360_https, $i_nd_Webm_360[0], $i_nd_Webm_360_https);       preg_match($itag_nondash_MP4_360_sig, $i_nd_Webm_360[0], $i_nd_Webm_360_sig);
//print_r($i_nd_MP4_360);
//print_r($i_nd_Webm_360_sig);

///////////////////////////////////////////////////////////////photo

$ForPhoto = '/og\:image\".+?tent=\"(.+?)\"/';
preg_match_all($ForPhoto, $html, $Photo, PREG_SET_ORDER, 0);
//echo "PHOTO " . $Photo[0][1];
//print_r($Photo);

//////////////////////////////////////////////////////////////////title
$ForTitle = '/og\:title\".+?tent=\"(.+?)\"/';
preg_match_all($ForTitle, $html, $Title, PREG_SET_ORDER, 0);

//////////////////////////////////////////////////////////////////title

////////////////////////////////////////////////////////////////photo
 /// //////////////////////////////////////////////////////// find base js
 $findbaseJS = '/src=\"(.+?base\.js)\"/';
 preg_match_all($findbaseJS, $html, $findedbaseJS, PREG_SET_ORDER, 0);
//echo "https://www.youtube.com". $findedbaseJS[0][1] . "<br>";
// echo "<br>";

 /// //////////////////////////////////////////////////////// find base js
   

/*
echo "<br>" . "<br>" . "NDasg_MP4_720= " . urldecode(urldecode($i_nd_MP4_720[1]));
echo "<br>" . "<br>" . "NDasg_MP4_360= " . urldecode(urldecode($i_nd_MP4_360[1]));
echo "<br>" . "<br>" . "NDasg_Webm_360= " . urldecode(urldecode($i_nd_Webm_360[1]));
*/
//echo "<br>";
//print_r($SigVideo720);
//echo "<br>" . "<br>" . "720 ciph = " .  urldecode(urldecode($SigVideo720[1]));

//echo "<br>" . "<br>" . "cign = " .  $SigVideo720[2];


//echo "<br>" . "<br>" ."720_1= " . $SigVideo720_1[1]; 
 //echo "<br>" . "<br>" . "720 ciph_2 = " .  urldecode(urldecode($SigVideo720_2[2]));
// echo "<br>" . "<br>" . "720 urld_1 = " .  urldecode(urldecode($SigVideo720_1[1]));

//echo "<br>" . "<br>" . "cign_2 = " .  $SigVideo720_2[1];
//echo "<br>" . "<br>";

//print_r($SigVideo720_2); echo "<br>";


$baseJS = file_get_contents("https://www.youtube.com".$findedbaseJS[0][1]);
//echo $baseJS;
///////////////////////////////////////////////////// regular for base.js
//$reJS = '/(..)=function\(a\){a=a.split\(\"\"\)\;(.+?)\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?\.(.+?)\(.+?\)\;.+?a\.join\(\"\"\)\}\;/'; 
$reJS = '/..=function\(a\)\{a=a\.split\(\"\"\).+?return.*?a\.join\(\"\"\)\}\;/';
preg_match($reJS, $baseJS, $parseJS, PREG_OFFSET_CAPTURE, 0);

//echo $parseJS[0][0] . "<br>";  //full  //$parseJS[0][0] -  $parseJS[9][0]
//echo $parseJS[1][0];  //name of function (AE=function)
//echo $parseJS[2][0];  //object xE ///
//echo $parseJS[3][0];  //method JR
//echo $parseJS[4][0];  //method mK


/////////////////////////////////////////////////////////////////////////////////////////////////////////// TT.Al TT.DE  = Al, DE
/*  // good old pars...
$reMethJS =  '/(..).*?\(\"\"\)\;(..)\.(..).*?\(.*?\.(.*?)\(.*?\.(.*?)\(.*?\.(.*?)\(.*?\.(.*?)\(.*?\./';
preg_match_all($reMethJS, $parseJS[0][0], $MethJS, PREG_SET_ORDER, 0);
//print_r ($MethJS);
//echo $MethJS[0][0] . "<br>";       //full  //$parseJS[0][0] -  $MethJS[9][0]
//echo $MethJS[0][1] . "<br>";  //name of function (AE=function)
//echo $MethJS[0][2] . "<br>";  //object xE ///
//echo $MethJS[0][3];  //method JR
//echo $MethJS[0][4];  //method mK
*/ 

$reFuncName = '/(..)\=/';
preg_match($reFuncName, $parseJS[0][0], $FuncName, PREG_OFFSET_CAPTURE, 0);
//print_r($FuncName);
//echo($FuncName[1][0]);    //(SE) = a.split 

$reMethALL = '/\.(..)\(/';
preg_match_all($reMethALL, $parseJS[0][0], $MethALL, PREG_SET_ORDER, 0);
/*
print_r($MethALL);
echo $MethALL[0][1]. "<br>";        //.(le)   .de  .we
echo $MethALL[1][1]. "<br>";
echo $MethALL[2][1]. "<br>";
echo $MethALL[3][1]. "<br>";
*/

///////////////////////////////////////////////////////////////////////

$reVarName = '/\;(..)\./';
preg_match_all($reVarName, $parseJS[0][0], $VarName, PREG_SET_ORDER, 0);
//print_r($VarName);
//echo $VarName[0][1];     //Var Name  (RE).ke

////////////////////////////////////////////////////////////////////////
/*
//$NumJS = '/\(.+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\)/';
$NumJS = '/\(.+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\).+?\,(\d+)\)/';
preg_match_all($NumJS, $parseJS[0][0], $Num, PREG_SET_ORDER, 0);             // $parseJS[0][0]
//print_r($Num);
// echo "NUM: " . $Num[0][1]. "<br>";      //working    $Num[0][1] - echo $Num[0][7]
*/

$NumJS = '/\,(\d+)\)/';                                                  //remaked
preg_match_all($NumJS, $parseJS[0][0], $Num, PREG_SET_ORDER, 0);  
/*
echo $Num[0][1]. "<br>";
echo $Num[1][1]. "<br>";
echo $Num[2][1]. "<br>";
echo $Num[3][1]. "<br>";
*/
$CountArr =  count($Num);
//echo $CountArr . " <-countMASS";

////////////////////////////////////////////////////////////////////////
 
 //echo "<br>"."<br>"."<br>";
 //$reJS_2 = '/var.('.$MethJS[0][2].')=\{(.+?):function(\(.+?\))\{(.+?)\}\,.+?(.+?):function(\(.+?\))\{(.+?)\}\,.+?(.+?):function(\(.+?\))\{(.+?)\}\}\;/s';
$reJS_2 = '/var.('.$VarName[0][1].')=\{(.+?):function(\(.+?\))\{(.+?)\}\,.+?(.+?):function(\(.+?\))\{(.+?)\}\,.+?(.+?):function(\(.+?\))\{(.+?)\}\}\;/s';
 preg_match($reJS_2, $baseJS, $parseJS_2, PREG_OFFSET_CAPTURE, 0);
/*
echo $parseJS_2[0][0] . "<br>";  //full                   $parseJS_2[0][0] - $parseJS_2[10][0]
echo $parseJS_2[1][0] . "<br>"; //object  (Var xE) 
echo $parseJS_2[2][0] . "<br>";  //name of 1-st method = mk:function 
echo $parseJS_2[3][0] . "<br>";  //param of 1-st method = (a) 
echo $parseJS_2[4][0] . "<br>";  //funct of 1-st method = a.splice(0,b) 
 */
 
 for ($j='4'; $j<='11'; $j+='3') { 
	if ($parseJS_2[$j][0]=="a.reverse()") {
		$For_h8=$parseJS_2[$j-2][0];
	//	echo "First: " . $For_h8 . "<br>";
	}
	else if ($parseJS_2[$j][0]=="a.splice(0,b)") {
		$For_dA=$parseJS_2[$j-2][0];
	//	echo "Second: " . $For_dA . "<br>";
	}
	else if ($parseJS_2[$j][0]=="var c=a[0];a[0]=a[b%a.length];a[b%a.length]=c") {
	    $For_Yi=$parseJS_2[$j-2][0]; 
	 // echo "Third: " . $For_Yi . "<br>";
	}
	//echo "please";
	//echo $parseJS_2[$j][0];
 }
 


 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
 class Decipher1 {
     
    public function DecA($a) {
		
//	global $parseJS;
    global $CountArr;
//	global $MethJS;
//	global $parseJS_2;
	global $Num;
	global $MethALL;
	global $For_h8;
	global $For_dA;
	global $For_Yi;
	$d='0';

    $a=str_split($a);
    
     
	
		for ($i='1'; $i<=$CountArr; $i++) {
		
		//	echo "For_h8= " . $For_h8;
		//	echo " {" . $MethALL[$d][1] .  " } " ;              //$parseJS[$i][0]
	    //	echo " (" . $Num[$d][1] . ") ";
	  
			if ($MethALL[$d][1] == $For_h8)  {                // ($MethJS[0][$i] == $For_h8)       $MethALL[2][1]
			//echo $MethALL[$k][1] . "<br>";
			//echo $Num[$d][1] . "<br>";
				$a=$this->h8($a,$Num[$d][1]);                //$a=$this->h8($a,$Num[0][$d]); 
			//	echo " work0 ";
			}
			else if ($MethALL[$d][1] == $For_dA) {                  //$parseJS[$i][0]
			$a=$this->dA($a,$Num[$d][1]); 						   //$a=$this->dA($a,$Num[0][$d]); 
			//	echo " work1 ";
			}
			else if ($MethALL[$d][1] == $For_Yi) {                //$parseJS[$i][0]
			   // print_r($a);
				$a=$this->Yi($a,$Num[$d][1]);               //$a=$this->Yi($a,$Num[0][$d]);
				//echo " work2 ";
			}
		$d++;
		}
	$a=join("", $a);
    return $this->a=$a;
     }
     
     public function h8($a) {      //Meth1
         $this->a=$a=array_reverse($a);
         return $this->a=$a;
     }
     public function dA($a,$b) {   //Meth2
         array_splice($a,0,$b); 
         return $this->a=$a;
     }
     public function Yi($a,$b) {   //Meth3
         $c=$a[0];                 //var c=a[0]
         $a[0]=$a[$b%count($a)];   //a[0]=a[b%a.length]
         $a[$b]=$c;                //a[b]=c
          return $this->a=$a;
         
     }
 }
 
 $reIF_no_prv = '/signature\%3/';
 preg_match ($reIF_no_prv, $i_nd_MP4_360[0], $IF_no_prv);
 //echo "(nPRV: " . $IF_no_prv[0] . " nPRV)";

 if ($IF_no_prv[0] == "signature%3"){
//echo "SigFor360";
	$title="&title=".$Title[0][1];
	$d_urldecode_360 = urldecode(urldecode($i_nd_MP4_360_https[1]));  //360
	$d_urldecode_360_Webm = urldecode(urldecode($i_nd_Webm_360_https[1]));  //360 WebM
	$d_urldecode = urldecode(urldecode($SigVideo720_1[1]));       //720
	
	//echo $d_urldecode_360;
	$re_d = '/videoplayback.*/';
	
  preg_match_all($re_d, $d_urldecode, $d, PREG_SET_ORDER, 0);
  $d_url='https://redirector.googlevideo.com/'.$d[0][0];
	
  preg_match_all($re_d, $d_urldecode_360, $d_360, PREG_SET_ORDER, 0);   //echo $d_360[0][0];
  $d_url_360='https://redirector.googlevideo.com/'.$d_360[0][0];
	
  preg_match_all($re_d, $d_urldecode_360_Webm, $d_360_Webm, PREG_SET_ORDER, 0);
  $d_url_360_Webm='https://redirector.googlevideo.com/'.$d_360_Webm[0][0];
  

  
	

	
	//$Video = $d_urldecode.$title;
	//$Video_360 = $d_urldecode_360.$title;
	//$Video_360_Webm = $d_urldecode_360_Webm.$title;
	$Video = $d_url.$title;
	$Video_360 = $d_url_360.$title;
	$Video_360_Webm = $d_url_360_Webm.$title;
	
	
	
	 
 }
 else {
// echo $i_nd_MP4_360[0] . "<br>" . "<br>";  
//print_r($i_nd_MP4_360); echo "<br>";
//print_r($i_nd_MP4_360_sig);
//echo $i_nd_MP4_360_sig[1] . "<br>" . "<br>";                                   
     //print_r($i_nd_MP4_360_https);
//	 echo $i_nd_MP4_360_https[1] . "<br>";                                           
	 //  echo urldecode(urldecode($i_nd_MP4_360_https[1]));
//echo $SigVideo720_2[1];
	 //print_r ($SigVideo720_2);
	  $res = new Decipher1();
	  
$d_urldecode = urldecode(urldecode($SigVideo720_1[1]));
$d_urldecode_360 = urldecode(urldecode($i_nd_MP4_360_https[1]));
$d_urldecode_360_Webm = urldecode(urldecode($i_nd_Webm_360_https[1]));
//echo "<br>" . "<br>";
 //$res = new Decipher1();
	 //$res->DecA($SigVideo720_2[1]);
  $signature = "&signature=";

//echo  "<br>"."<br>";
//echo $res->DecA($SigVideo720_2[1]);
     //$d_urldecode_360 = urldecode(urldecode($i_nd_MP4_360[1]));
 $title="&title=".$Title[0][1];
 
 
 	$re_d = '/videoplayback.*/';
 
   preg_match_all($re_d, $d_urldecode, $d, PREG_SET_ORDER, 0);
  $d_url='https://redirector.googlevideo.com/'.$d[0][0];
	
  preg_match_all($re_d, $d_urldecode_360, $d_360, PREG_SET_ORDER, 0);   //echo $d_360[0][0];
  $d_url_360='https://redirector.googlevideo.com/'.$d_360[0][0];
	
  preg_match_all($re_d, $d_urldecode_360_Webm, $d_360_Webm, PREG_SET_ORDER, 0);
  $d_url_360_Webm='https://redirector.googlevideo.com/'.$d_360_Webm[0][0];
  
 
  $reMP4 = '/mp4/';
 preg_match_all($reMP4, $d_url, $MP4_720, PREG_SET_ORDER, 0);


 if ($MP4_720 != NULL) {
	$Video = $d_url.$title.$signature.$res->DecA($SigVideo720_2[1]);
	//echo $Video;
	}

 
 //$Video = $d_url.$title.$signature.$res->DecA($SigVideo720_2[1]);
     //echo  $res->DecA($i_nd_MP4_360_sig[1]);
     // echo $d_urldecode_360.$title.$signature.$res->DecA($i_nd_MP4_360_sig[1]);
 $Video_360 = $d_url_360.$title.$signature.$res->DecA($i_nd_MP4_360_sig[1]);
 $Video_360_Webm = $d_url_360_Webm.$title.$signature.$res->DecA($i_nd_Webm_360_sig[1]);
    //echo $Video_360;
	
 }
  
    //echo "<br>" . "<br>" . "720 urld_1 = " .  urldecode(urldecode($SigVideo720_1[1]));
    //echo "&signature=" .  $res->DecA($SigVideo720_2[1]);
  
  
   // echo "sig= " . $res->DecA("7979FA9FAB95A905B3723835A98EF10A1756E7E0E286F6.BA3E9D05CF1DAB14C8B9ECF5C7A56CE4E1159DD6");  //for one sign
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*   echo $parseJS_2[2][0];
	 echo $parseJS_2[5][0];
	 echo $parseJS_2[8][0];
*/


 }


?>
<html>
<link rel="stylesheet" type="text/css" href="FB.css">




<?php
if (isset($Photo[0][1])) {
	echo "<div class='a1-div1'>   <video width='500' height='400' poster='" . $Photo[0][1] ."' controls='' name='media'> <source src='".$Video_360."' type='video/mp4' download='360p Quality' >   </video>  </div>";
	
	if (isset($Video)) {
		echo "<div class='a1-div2'> <a align='center' id='a1-div-a' class='text-center' href='".$Video."'   type='video/mp4' download='720p Quality' >Download 720p MP4</a> </div> ";
		echo "<br><br><br>";
		}
	if (isset($Video_360)) {	
		echo " <div class='a1-div2'> <a align='center' id='a1-div-a' class='text-center' href='".$Video_360."'   type='video/mp4' download='360p Quality' >Download 360p MP4</a> </div>";
		echo "<br><br><br>";
		}
	if (isset($Video_360_Webm)) {
		echo "<div class='a1-div2'> <a align='center' id='a1-div-a' class='text-center' href='".$Video_360_Webm."'   type='video/mp4' download='360p Quality' >Download 360p WebM</a>  </div>";
		echo "<br><br><br>";
		}
}		
		

	?>
	
</html>