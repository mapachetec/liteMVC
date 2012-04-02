<?php if(! defined('ABSPATH')) die("Access is forbidden.");
ob_start();
function isEmpty($array){
	return (count(array_filter($array)) == 0) ? true : false;
}

function get_results($query,$data=array())
{
	global $DBH;
	if(!is_array($data)||(is_array($data)&&isEmpty($data))) return false;
	try{
		$st=$DBH->prepare($query);
		if (!$st->execute($data)) return false;
	}
	catch(PDOException $e) {
	    return false;
	}
	
	return ($st->rowCount()==0)?false:$st;
			
}

function do_query($query,$data=array(),$lastID=false)
{
	global $DBH;
	if(!is_array($data)||(is_array($data)&&isEmpty($data))) return false;
	
	try{
		$st=$DBH->prepare($query);
		$st->execute($data);
		return (!$lastID)?$st->rowCount():array('done'=>$st->rowCount(),'lastID'=>$DBH->lastInsertId());
	}
	catch(PDOException $e) {
	    return false;
	}
}

function dateDiff($start, $end) {

		$start_ts = strtotime($start);
		
		$end_ts = strtotime($end);
		
		$diff = $end_ts - $start_ts;
		
		return round($diff / 86400);

}

function setData($data=array(),$method='post'){
	$error=0;
	if(!is_array($data)||(is_array($data)&&isEmpty($data))||($method!='post'&&$method!='get')) return false;
	
	foreach($data as $k){
		if($method=='post'){
			//if(preg_match('/^\s+$/',$_POST[$k])||!isset($_POST[$k])){
			if(trim($_POST[$k])==''||!isset($_POST[$k])){
				$error=1;break;
			}
		}
		if($method=='get'){
			if(trim($_GET[$k]=='')||!isset($_GET[$k])){
				$error=1;break;
			}
		}
	}
	
	return $error==0?true:false;
}

function go_section($mod='home',$action=false,$more_vars=false){
	if($action){
		if($more_vars)
			return ADD_MOD.$mod.'&'.CALL_ACTION.'='.$action.'&'.$more_vars;
		else
			return ADD_MOD.$mod.'&'.CALL_ACTION.'='.$action;
	 }
	else{
		if($more_vars)
			return ADD_MOD.$mod.'&'.$more_vars;
		else
			return ADD_MOD.$mod;
	}
	
}
/***********************************************************/

function more_text( $str, $num, $msg='' ) {
  $w = preg_split( '/[\s]+/', $str, -1, PREG_SPLIT_OFFSET_CAPTURE );
  if( isset($w[$num][1]) ){
    $str = substr( $str, 0, $w[$num][1] ) .'...'.$msg;
  }
  unset( $w, $num );
  return trim( $str );
}


function weekday($fecha){
    $fecha=str_replace("/","-",$fecha);
    list($dia,$mes,$anio)=explode("-",$fecha);
    return ((((mktime ( 0, 0, 0, $mes, $dia, $anio) - mktime ( 0, 0, 0, 7, 17, 2006))/(60*60*24))+700000) % 7)+1;
}

function mifecha($cadena){
	$diasEs=Array("lunes","martes","mi&eacute;rcoles","jueves","viernes","s&aacute;bado","domingo");
	$mesesEs=Array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
	$diasEn=Array("Monday","Tuesday","Wednesday","Thursday","Friday", "Saturday","Sunday");
	$mesesEn=Array("January", "February", "March", "April", "May", "June","July","August", "September", "October", "November", "December");
	$horasEn=Array("th", "st", "nd", "rd");
	$horasEs=array("","","");
	return	str_replace($horasEn,$horasEs,str_replace($mesesEn,$mesesEs,str_replace($diasEn,$diasEs,$cadena)));

}

function FilterHTML($string) {
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    $string = html_entity_decode($string, ENT_QUOTES, "ISO-8859-1");
    // convert decimal
    $string = preg_replace('/&#(\d+)/me', "chr(\\1)", $string); // decimal notation
    // convert hex
    $string = preg_replace('/&#x([a-f0-9]+)/mei', "chr(0x\\1)", $string); // hex notation
    //$string = html_entity_decode($string, ENT_COMPAT, "UTF-8");
    $string = preg_replace('#(&\#*\w+)[\x00-\x20]+;#U', "$1;", $string);
    $string = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU', "$1>", $string);
    //$string = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', "$1$2;", $string); //bad line
    $string = preg_replace('#/*\*()[^>]*\*/#i', "", $string); // REMOVE /**/
    $string = preg_replace('#([a-z]*)[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '...', $string); //JAVASCRIPT
    $string = preg_replace('#([a-z]*)([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU', '...', $string); //VBSCRIPT
    $string = preg_replace('#([a-z]*)[\x00-\x20]*([\\\]*)[\\x00-\x20]*@([\\\]*)[\x00-\x20]*i([\\\]*)[\x00-\x20]*m([\\\]*)[\x00-\x20]*p([\\\]*)[\x00-\x20]*o([\\\]*)[\x00-\x20]*r([\\\]*)[\x00-\x20]*t#iU', '...', $string); //@IMPORT
    $string = preg_replace('#([a-z]*)[\x00-\x20]*e[\x00-\x20]*x[\x00-\x20]*p[\x00-\x20]*r[\x00-\x20]*e[\x00-\x20]*s[\x00-\x20]*s[\x00-\x20]*i[\x00-\x20]*o[\x00-\x20]*n#iU', '...', $string); //EXPRESSION
    $string = preg_replace('#</*\w+:\w[^>]*>#i', "", $string);
    $string = preg_replace('#</?t(able|r|d)(\s[^>]*)?>#i', '', $string); // strip out tables
    $string = preg_replace('/(potspace|pot space|rateuser|marquee)/i', '...', $string); // filter some words
    //$string = str_replace('left:0px; top: 0px;','',$string);
    do {
        $oldstring = $string;
        //bgsound|
        $string = preg_replace('#</*(applet|meta|xml|blink|link|script|iframe|frame|frameset|ilayer|layer|title|base|body|xml|AllowScriptAccess|big)[^>]*>#i', "...", $string);
    } while ($oldstring != $string);
    return addslashes($string);
}

	
/**
 * Obtiene el Current Server Name
 *
 * @return string
 */
function currentURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
	

function is_valid_ip($ip){
	return preg_match("/[[:digit:]]{1,3}[\.][[:digit:]]{1,3}[\.][[:digit:]]{1,3}[\.][[:digit:]]{1,3}/",$ip);
		
}


function seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}


/**
 * Quitar acentos.
 *
 * @since 1.2.1
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '');

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;

}

function sanitize_string($username,$strip_too=false){
	$raw_username = $username;
	$username = strip_all_tags( $username );
	//$username = remove_accents( $username );
	// Kill octets
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

	// If strict, reduce to ASCII for max portability.
	if ( $strict )
		$username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $username );

	// Consolidate contiguous whitespace
	$username = preg_replace( '|\s+|', ' ', $username );
	$username = str_replace("'", '', $username);
	$username = str_replace("\\", '', $username);
	$username = str_replace("\"", '', $username);

	return ($strip_too)?strip_all_tags($username):$username;
}

function sanitize_title_with_dashes($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	$title = remove_accents($title);
	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}
	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}


function sanitize_user( $username, $strict = false ) {
	$raw_username = $username;
	$username = strip_all_tags( $username );
	$username = remove_accents( $username );
	// Kill octets
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

	// If strict, reduce to ASCII for max portability.
	if ( $strict )
		$username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $username );

	// Consolidate contiguous whitespace
	$username = preg_replace( '|\s+|', ' ', $username );

	return $username;
}

/**
 * Properly strip all HTML tags including script and style
 *
 * @since 2.9.0
 *
 * @param string $string String containing HTML tags
 * @param bool $remove_breaks optional Whether to remove left over line breaks and white space chars
 * @return string The processed string.
 */
function strip_all_tags($string, $remove_breaks = false, $remove_brs=false) {
	$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	if ( $remove_brs )
		$string = strip_tags($string);
	else
		$string = strip_tags($string,'<br>');

	if ( $remove_breaks )
		$string = preg_replace('/[\r\n\t ]+/', ' ', $string);

	return trim($string);
}


/**
 * Encode the Unicode values to be used in the URI.
 *
 * @since 1.5.0
 *
 * @param string $utf8_string
 * @param int $length Max length of the string
 * @return string String with Unicode encoded for URI.
 */
function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

			$values[] = $value;

			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				if ($num_octets == 3) {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					$unicode_length += 9;
				} else {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					$unicode_length += 6;
				}

				$values = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}

/**
 * Checa si es un formato de MAIL valido
 * @param string
 * @return boolean
 */
function is_a_mail($mail){
	if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
		return false;
	
	return true;
}

function redirect($u){
	header("location:".$u);
	exit(0);
}
function is_a_url($url){
	return preg_match('/^(https?:\/\/)?[\w\d-]+\.[a-zA-Z]+/',$url);
}
###############################################

	
	
	function bold_arroba($str,$needle="-")
	{
		return preg_replace('/(^|\s)@(\w+)/',"<b> @$2</b>",$str);
	}
	
	function do_compatible($str)
	{
		if(seems_utf8($str))
			return (addslashes(htmlentities(utf8_decode($str))));
		
		return addslashes(htmlentities(urldecode($str)));
		
	}
	



function get_date($f='')
{
	switch ($f) {
		case 'hora':
			return date("H:i:s");
			break;
			
		case 'year':
			return date("Y-n-j");
			break;
	
		default:
			return date("Y-n-j H:i:s");
			break;
	}
	
}
function get_ip(){
	return (!empty($HTTP_SERVER_VARS['REMOTE_ADDR']))?$HTTP_SERVER_VARS['REMOTE_ADDR']:((!empty($HTTP_ENV_VARS['REMOTE_ADDR']))?$HTTP_ENV_VARS['REMOTE_ADDR']:getenv('REMOTE_ADDR'));
}

function enviar_mail($autor,$mailde,$mailpara,$asunto,$mensaje)
	{
		

		if(!is_a_mail($mailde) || !is_a_mail($mailpara))
			return false;

			
		$header = 'From: ' . $mailde . " \r\n";
		$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
		$header .= "Mime-Version: 1.0 \r\n";
		$header .= "Content-Type: text/html;charset=utf-8\r\n";

		$mensaje='
				<b style="color:#2F53FF">Datos del remitente:</b>
				<div style="font-size:11px" >
					<b>Nombre</b>: '.$autor.'<br/>
					<b>Correo</b>: '.$mailde.'<br/>
					<b>Fecha</b>: '.get_date().'<br/>
					<b>IP</b>: '.get_ip().'<br/>
					<br/>
				</div>
				<b style="color:#2F53FF">mensaje:</b>
				<div style="padding:30px;color:#000;border:solid 1px #408ABD;background:#CDE5EC">
					
						'.$mensaje.'
					
				</div>
		';
		
		if(!@mail($mailpara, utf8_encode($asunto), utf8_encode($mensaje), $header))
			return false;
			
		
		return true;
		
	}
	
	function mailto_friend($autor,$mailde,$mailpara,$asunto,$mensaje,$senderSys){
		if(!is_a_mail($mailde) || !is_a_mail($mailpara) || !is_a_mail($senderSys))
			return false;

			
		$header = 'From: ' . $senderSys . " \r\n";
		$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
		$header .= "Mime-Version: 1.0 \r\n";
		$header .= "Content-Type: text/html;charset=utf-8\r\n";

		$mensaje='
				<div style="font-size:11px" >
					<b style="color:darkred;font-size:15px">Datos del remitente:</b><br/>
					<b>Nombre</b>: '.$autor.'<br/>
					<b>Correo</b>: '.$mailde.'<br/>
					<b>Fecha</b>: '.get_date().'<br/>
					<br/>
				</div>
				<div style="clear:both;margin:5px 0"></div>
				<b style="color:darkred">mensaje:</b>
				<div style="padding:20px 30px;color:#333;border:1px solid #C99B04;background:#FFF69E;font:14px sans-serif,Geneva">
					
						'.$mensaje.'
					
				</div>
		';
		
		if(!@mail($mailpara, $asunto, $mensaje, $header))
			return false;
			
		
		return true;
	}
	
/**
 *
 * @convert BMP to GD
 *
 * @param string $src
 *
 * @param string|bool $dest
 *
 * @return bool
 *
 */
function bmp2gd($src, $dest = false)
{
	    /*** try to open the file for reading ***/
	    if(!($src_f = fopen($src, "rb")))
	    {
	        return false;
	    }
	
	/*** try to open the destination file for writing ***/
	if(!($dest_f = fopen($dest, "wb")))
	    {
	        return false;
	    }
	
	/*** grab the header ***/
	$header = unpack("vtype/Vsize/v2reserved/Voffset", fread( $src_f, 14));
	
	/*** grab the rest of the image ***/
	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",
	fread($src_f, 40));
	
	/*** extract the header and info into varibles ***/
	extract($info);
	extract($header);
	
	/*** check for BMP signature ***/
	if($type != 0x4D42)
	{
	    return false;
	}
	
	/*** set the pallete ***/
	$palette_size = $offset - 54;
	$ncolor = $palette_size / 4;
	$gd_header = "";
	
	/*** true-color vs. palette ***/
	$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
	$gd_header .= pack("n2", $width, $height);
	$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
	if($palette_size) {
	$gd_header .= pack("n", $ncolor);
	}
	/*** we do not allow transparency ***/
	$gd_header .= "\xFF\xFF\xFF\xFF";
	
	/*** write the destination headers ***/
	fwrite($dest_f, $gd_header);
	
	/*** if we have a valid palette ***/
	if($palette_size)
	{
	    /*** read the palette ***/
	    $palette = fread($src_f, $palette_size);
	    /*** begin the gd palette ***/
	    $gd_palette = "";
	    $j = 0;
	    /*** loop of the palette ***/
	    while($j < $palette_size)
	    {
	        $b = $palette{$j++};
	        $g = $palette{$j++};
	        $r = $palette{$j++};
	        $a = $palette{$j++};
	        /*** assemble the gd palette ***/
	        $gd_palette .= "$r$g$b$a";
	    }
	    /*** finish the palette ***/
	    $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
	    /*** write the gd palette ***/
	    fwrite($dest_f, $gd_palette);
	}
	
	/*** scan line size and alignment ***/
	$scan_line_size = (($bits * $width) + 7) >> 3;
	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;
	
	/*** this is where the work is done ***/
	for($i = 0, $l = $height - 1; $i < $height; $i++, $l--)
	{
	    /*** create scan lines starting from bottom ***/
	    fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
	    $scan_line = fread($src_f, $scan_line_size);
	    if($bits == 24)
	    {
	        $gd_scan_line = "";
	        $j = 0;
	        while($j < $scan_line_size)
	        {
	            $b = $scan_line{$j++};
	            $g = $scan_line{$j++};
	            $r = $scan_line{$j++};
	            $gd_scan_line .= "\x00$r$g$b";
	        }
	    }
	    elseif($bits == 8)
	    {
	        $gd_scan_line = $scan_line;
	    }
	    elseif($bits == 4)
	    {
	        $gd_scan_line = "";
	        $j = 0;
	        while($j < $scan_line_size)
	        {
	            $byte = ord($scan_line{$j++});
	            $p1 = chr($byte >> 4);
	            $p2 = chr($byte & 0x0F);
	            $gd_scan_line .= "$p1$p2";
	        }
	        $gd_scan_line = substr($gd_scan_line, 0, $width);
	    }
	    elseif($bits == 1)
	    {
	        $gd_scan_line = "";
	        $j = 0;
	        while($j < $scan_line_size)
	        {
	            $byte = ord($scan_line{$j++});
	            $p1 = chr((int) (($byte & 0x80) != 0));
	            $p2 = chr((int) (($byte & 0x40) != 0));
	            $p3 = chr((int) (($byte & 0x20) != 0));
	            $p4 = chr((int) (($byte & 0x10) != 0));
	            $p5 = chr((int) (($byte & 0x08) != 0));
	            $p6 = chr((int) (($byte & 0x04) != 0));
	            $p7 = chr((int) (($byte & 0x02) != 0));
	            $p8 = chr((int) (($byte & 0x01) != 0));
	            $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
	        }
	    /*** put the gd scan lines together ***/
	    $gd_scan_line = substr($gd_scan_line, 0, $width);
	    }
	    /*** write the gd scan lines ***/
	    fwrite($dest_f, $gd_scan_line);
	}
	/*** close the source file ***/
	fclose($src_f);
	/*** close the destination file ***/
	fclose($dest_f);
	
	return true;
}

/**
 *
 * @ceate a BMP image
 *
 * @param string $filename
 *
 * @return bin string on success
 *
 * @return bool false on failure
 *
 */
function ImageCreateFromBmp($filename)
{
    /*** create a temp file ***/
    $tmp_name = tempnam("/tmp", "GD");
    /*** convert to gd ***/
    if(bmp2gd($filename, $tmp_name))
    {
        /*** create new image ***/
        $img = imagecreatefromgd($tmp_name);
        /*** remove temp file ***/
        unlink($tmp_name);
        /*** return the image ***/
        return $img;
    }
    return false;
}

function time_to_str($timestamp,$show_hour=true)
{
	$data=explode(" ",$timestamp);
	$f_date=getdate(strtotime($timestamp));
	
	if(!$show_hour)
		return mifecha("$f_date[mday] de $f_date[month] de $f_date[year]");
		
	return mifecha("$f_date[mday] de $f_date[month] de $f_date[year], $data[1]hrs.");
}


function show_twitter_widget($t,$w=230,$h=300){
	
	?>
		<script src="http://widgets.twimg.com/j/2/widget.js"></script>
		<script>
				new TWTR.Widget({
				version: 2,
				type: 'profile',
				rpp: 10,
				interval: 6000,
				width: <?=$w?>,
				height: <?=$h?>,
				theme: {
				shell: {
				  background: '#eee',
				  color: '#333'
				},
				tweets: {
				  background: '#ffffff',
			      color: '#444444',
			      links: '#c30206'
				}
				},
				features: {
				scrollbar: false,
				loop: true,
				live: true,
				hashtags: true,
				timestamp: true,
				avatars: false,
				behavior: 'default'
				}
				}).render().setUser('<?=$t?>').start();
		</script>
	<?php
}

	
	
	function is_a_bot(){
		
		$robot[] = "googlebot";  
		$robot[] = "google"; 
		$robot[] = "mediapartners"; 
		$robot[] = "msnbot";  
		$robot[] = "msn"; 
		$robot[] = "overture";  
		$robot[] = "lycos";  
		$robot[] = "seek";  
		$robot[] = "inktomi";  
		$robot[] = "yahoo";  
		$robot[] = "slurp"; 
		$robot[] = "altavista";  
		$robot[] = "alexa";  
		$robot[] = "crawler";  
		$robot[] = "bingbot";  
		$robot[] = "baiduspider";
		$robot[] = "spider";
		
		$isRobot=false;
		  
		foreach($robot as $bot){ 
		   $str = strtolower($_SERVER['HTTP_USER_AGENT']); 
		   if (strpos($str, $bot) !== false ){ 
		       $isRobot=true;  
		       break;  
		    }  
		}  
		
		if($isRobot)
			return $bot;
		
		return false;
		   
	}
	
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
	
	
	/*###########################################################################
					CLIMAAA
	#############################################################################*/
	function get_clima_data($city='chetumal',$lang='es'){
				
		$url='http://www.google.com/ig/api?weather='.$city.'&oe=utf-8&hl='.$lang;
		
		$xml = @simplexml_load_file($url);
		
		return $xml;
	}
	
	function clima_report($city='chetumal',$lang='es'){ 
		$xml = get_clima_data($city,$lang);
		$information = $xml->xpath("/xml_api_reply/weather/forecast_information");
		$current = $xml->xpath("/xml_api_reply/weather/current_conditions");
		$weather_forecast = $xml->xpath("/xml_api_reply/weather/forecast_conditions");
		$w_c=0;
		?>
		<h6 class="w_city"><?= htmlentities(utf8_decode($information[0]->city['data'])); ?></h6>
		<img alt="<?=$current[0]->condition['data']?>" title="<?=$current[0]->condition['data']?>" src="http://sqcs.com.mx/images/clima/<?=(basename($current[0]->icon['data'],'.gif')=='')?'na':basename($current[0]->icon['data'],'.gif')?>.png" class="fll" style="margin:0 6px 0 0" />
	    <span class="w_temp">
			<?=$current[0]->temp_c['data'] ?> &deg;C
		</span><br/>
	    <span class="w_condition">
			<?=$current[0]->wind_condition['data'] ?>,
		</span>
		<span class="w_humedad">
	    	<?=$current[0]->humidity['data']?><br />
	    </span>
	    <b><?=htmlentities(utf8_decode($current[0]->condition['data']))?></b>
		
		<div class="clearall" ></div>
		<hr/>
		<div id="forecast" class="textc" style="width:160px;margin:0 auto;color:#B50909;font-size:11px">
			
			<?php foreach ($weather_forecast as $f) : ?>
				
				<?php if($w_c>0): ?>
					<div id="item" class="fll">
			    		<?= htmlentities(utf8_decode($f->day_of_week['data'])); ?><br />
						<img class="tips" src="http://sqcs.com.mx/images/clima/<?=basename($f->icon['data'],'.gif')?>.png" width="45" alt="<?= $f->condition['data']; ?>" title="<?= htmlentities(utf8_decode($f->condition['data'])) ?>" rel="Min:<?=$f->low['data']?>&deg;C | Max: <?=$f->high['data']?>&deg;C"/>
			    
					</div>
				<?php endif ?>
				<?php $w_c++; ?>
				
		    <?php endforeach ?>
		    <div class="clearall" ></div>
		 </div>
		<script type="text/javascript">
			new Tips('.tips');
		</script>
<?php 
	}
	
	function show_clima(){ 
		if(!get_clima_data()){
			echo "Clima no disponible";
			return;
		}
		?>
		
		<div id="slide_clima">
			<div id="clima_box" style="position:absolute">
				<span class="clima_item">
			   		<?php clima_report() ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('cancun, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('felipe carrillo puerto, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('tulum, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('isla mujeres, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('jose maria morelos, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('playa del carmen, qroo') ?>
				</span>
				<span class="clima_item">
			   		<?php clima_report('cozumel, qroo') ?>
				</span>
				
			 </div>
		</div>
		<script type="text/javascript">
			window.addEvent('domready',function()
			{
				var climaSlide = new noobSlide({
					box: document.id('clima_box'),
					items: $$('.clima_item'),
					size: 225,
					interval: 10000,
					//fxOptions: fxOptions,
					mode:'vertical',
					autoPlay:true
				});
				
			})//ENDOM
			
		</script>
<?php 

	}
?>
<?php 
	

function set_message($type,$msg){
	global $message;
	switch($type){
		case 'error':
			$id='msgError';
			break;
		case 'confirm':
			$id='msgConfirm';
			break;
		case 'warning':
			$id='msgWarning';
			break;
		case 'info':
			$id='msgInfo';
			break;
		default:$msg='';break;
	}
	
	if(!empty($msg)){
		$message='<div class="message" id="'.$id.'" >'.$msg.'</div>';
	}
}	


function sanitize_sql_orderby( $orderby ){
	preg_match('/^\s*([a-z0-9_]+(\s+(ASC|DESC))?(\s*,\s*|\s*$))+|^\s*RAND\(\s*\)\s*$/i', $orderby, $obmatches);
	if ( !$obmatches )
			return false;
	return $orderby;
}

function protect_session($rol=false){
	$rolls=array('admin','user');
	
	if (isset($_SESSION['HTTP_USER_AGENT'])){
	    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])||$_SESSION['ip']!=get_ip()){
			redirect(ADD_MOD.'logout');
	    }
	}
	
	else{
		redirect("?".CALL_MOD."=user&".CALL_ACTION."=login");
	}
	
	if($rol){
		
		if($_SESSION['nivel']!=$rol)
			redirect(ADD_MOD.'logout');
	}

}

function load_view($file,$data=null,$ext=null){
	global $message,$action,$module;

	if(is_array($data)&&!isEmpty($data)){
		foreach ($data as $k => $v){
			$$k=$v;
		}
	}
	$f=($ext)?$file.".".$ext:$file.EXT_DEFAULT;
	if(file_exists(PATH_VIEW.$f))
		require_once(PATH_VIEW.$f);
	else
		die("<h4>Error 404</h4>");
}

function getStyle($global=true,$buttons=true){ global $folderModules,$pathModules,$module ?>
	<?php if($global): ?>
	<link rel="stylesheet" type="text/css" href="<?=INDEXPATH."css/globalStyle.css"?>" media="screen" />
	<?php endif ?>
	<?php if($buttons): ?>
	<link rel="stylesheet" type="text/css" href="<?=INDEXPATH."css/css-buttons.css"?>" media="screen" />
	<?php endif ?>
	<?php if(file_exists(PATH_VIEW.'/style.css')): ?>
<link rel="stylesheet" type="text/css" href=" <?=VIEW_FILES?>style.css" media="screen" />
	<?php endif ?>
 
<?php } ?>

<?php 
	function twitter_users($str,$needle="-")
	{
		//return preg_replace('/(^|\s)*@(\w+[\-\.\:]?)/',"<a href=\"http://twitter.com/$2\" target=\"_blank\" class='usersTw'>@$2</a>",$str);
		return preg_replace('/(^|\s)*@(\w+)/',"<a href=\"http://twitter.com/$2\" target=\"_blank\" class='usersTw'>@$2</a>",$str);
	}?>
