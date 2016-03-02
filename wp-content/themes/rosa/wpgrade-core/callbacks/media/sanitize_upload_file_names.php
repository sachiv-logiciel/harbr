<?php
/*  for PRO users! - =========== SANITIZE UPLOADED FILE NAMES ==========*/

add_filter( 'sanitize_file_name', 'wpgrade_sanitize_file_name', 10 );

/*  for PRO users! - *
 * Clean up uploaded file names
 * @author toscho
 * @url    https://github.com/toscho/Germanix-WordPress-Plugin
 */
function wpgrade_sanitize_file_name( $filename ) {
	$filename = html_entity_decode( $filename, ENT_QUOTES, 'utf-8' );
	$filename = wpgrade_translit( $filename );
	$filename = wpgrade_lower_ascii( $filename );
	$filename = wpgrade_remove_doubles( $filename );

	return $filename;
}

function wpgrade_lower_ascii( $str ) {
	$str   = strtolower( $str );
	$regex = array(
		'pattern'     => '~([^a-z\d_.-])~',
		'replacement' => ''
	);
	// Leave underscores, otherwise the taxonomy tag cloud in the
	// backend won’t work anymore.
	return preg_replace( $regex['pattern'], $regex['replacement'], $str );
}

/*  for PRO users! - *
 * Reduces repeated meta characters (-=+.) to one.
 */
function wpgrade_remove_doubles( $str ) {
	$regex = apply_filters( 'germanix_remove_doubles_regex', array(
		'pattern'     => '~([=+.-])\\1+~',
		'replacement' => "\\1"
	) );

	return preg_replace( $regex['pattern'], $regex['replacement'], $str );
}

/*  for PRO users! - *
 * Replaces non ASCII chars.
 */
function wpgrade_translit( $str ) {
	$utf8 = array(
		'Ä'  => 'Ae',
		'ä'  => 'ae',
		'Æ'  => 'Ae',
		'æ'  => 'ae',
		'À'  => 'A',
		'à'  => 'a',
		'�?'  => 'A',
		'á'  => 'a',
		'Â'  => 'A',
		'â'  => 'a',
		'�?'  => 'A',
		'ã'  => 'a',
		'Å'  => 'A',
		'å'  => 'a',
		'ª'  => 'a',
		'�?'  => 'a',
		'�?'  => 'a',
		'Ć'  => 'C',
		'ć'  => 'c',
		'Ç'  => 'C',
		'ç'  => 'c',
		'�?'  => 'D',
		'đ'  => 'd',
		'�?'  => 'E',
		'è'  => 'e',
		'É'  => 'E',
		'é'  => 'e',
		'Ê'  => 'E',
		'ê'  => 'e',
		'Ë'  => 'E',
		'ë'  => 'e',
		'ₑ'  => 'e',
		'ƒ'  => 'f',
		'ğ'  => 'g',
		'Ğ'  => 'G',
		'Ì'  => 'I',
		'ì'  => 'i',
		'Í'  => 'I',
		'í'  => 'i',
		'Î'  => 'I',
		'î'  => 'i',
		'Ï'  => 'Ii',
		'ï'  => 'ii',
		'ī'  => 'i',
		'ı'  => 'i',
		'I'  => 'I' // turkish, correct?
	,
		'Ñ'  => 'N',
		'ñ'  => 'n',
		'�?�'  => 'n',
		'Ò'  => 'O',
		'ò'  => 'o',
		'Ó'  => 'O',
		'ó'  => 'o',
		'Ô'  => 'O',
		'ô'  => 'o',
		'Õ'  => 'O',
		'õ'  => 'o',
		'�?'  => 'O',
		'ø'  => 'o',
		'ₒ'  => 'o',
		'Ö'  => 'Oe',
		'ö'  => 'oe',
		'Œ'  => 'Oe',
		'œ'  => 'oe',
		'ß'  => 'ss',
		'Š'  => 'S',
		'š'  => 's',
		'ş'  => 's',
		'Ş'  => 'S',
		'™'  => 'TM',
		'Ù'  => 'U',
		'ù'  => 'u',
		'Ú'  => 'U',
		'ú'  => 'u',
		'Û'  => 'U',
		'û'  => 'u',
		'Ü'  => 'Ue',
		'ü'  => 'ue',
		'Ý'  => 'Y',
		'ý'  => 'y',
		'ÿ'  => 'y',
		'Ž'  => 'Z',
		'ž'  => 'z' // misc
	,
		'¢'  => 'Cent',
		'€'  => 'Euro',
		'‰'  => 'promille',
		'№'  => 'Nr',
		'$'  => 'Dollar',
		'�?'  => 'Grad Celsius',
		'°C' => 'Grad Celsius',
		'℉'  => 'Grad Fahrenheit',
		'°F' => 'Grad Fahrenheit' // Superscripts
	,
		'�?�'  => '0',
		'¹'  => '1',
		'²'  => '2',
		'³'  => '3',
		'�?�'  => '4',
		'�?�'  => '5',
		'�?�'  => '6',
		'�?�'  => '7',
		'�?�'  => '8',
		'�?�'  => '9' // Subscripts
	,
		'₀'  => '0',
		'�?'  => '1',
		'₂'  => '2',
		'�?'  => '3',
		'₄'  => '4',
		'₅'  => '5',
		'₆'  => '6',
		'₇'  => '7',
		'�?'  => '8',
		'₉'  => '9' // Operators, punctuation
	,
		'±'  => 'plusminus',
		'×'  => 'x',
		'₊'  => 'plus',
		'₌'  => '=',
		'�?�'  => '=',
		'�?�'  => '-' // sup minus
	,
		'₋'  => '-' // sub minus
	,
		'–'  => '-' // ndash
	,
		'—'  => '-' // mdash
	,
		'‑'  => '-' // non breaking hyphen
	,
		'․'  => '.' // one dot leader
	,
		'‥'  => '..' // two dot leader
	,
		'…'  => '...' // ellipsis
	,
		'‧'  => '.' // hyphenation point
	,
		' '  => '-' // nobreak space
	,
		' '  => '-' // normal space
		// Russian
	,
		'�?'  => 'A',
		'Б'  => 'B',
		'В'  => 'V',
		'Г'  => 'G',
		'Д'  => 'D',
		'Е'  => 'E',
		'�?'  => 'YO',
		'Ж'  => 'ZH',
		'З'  => 'Z',
		'�?'  => 'I',
		'Й'  => 'Y',
		'К'  => 'K',
		'Л'  => 'L',
		'М'  => 'M',
		'Н'  => 'N',
		'О'  => 'O',
		'П'  => 'P',
		'Р'  => 'R',
		'С'  => 'S',
		'Т'  => 'T',
		'У'  => 'U',
		'Ф'  => 'F',
		'Х'  => 'H',
		'Ц'  => 'TS',
		'Ч'  => 'CH',
		'Ш'  => 'SH',
		'Щ'  => 'SCH',
		'Ъ'  => '',
		'Ы'  => 'YI',
		'Ь'  => '',
		'Э'  => 'E',
		'Ю'  => 'YU',
		'Я'  => 'YA',
		'а'  => 'a',
		'б'  => 'b',
		'в'  => 'v',
		'г'  => 'g',
		'д'  => 'd',
		'е'  => 'e',
		'ё'  => 'yo',
		'ж'  => 'zh',
		'з'  => 'z',
		'и'  => 'i',
		'й'  => 'y',
		'к'  => 'k',
		'л'  => 'l',
		'м'  => 'm',
		'н'  => 'n',
		'о'  => 'o',
		'п'  => 'p',
		'р'  => 'r',
		'�?'  => 's',
		'т'  => 't',
		'�?'  => 'u',
		'ф'  => 'f',
		'х'  => 'h',
		'ц'  => 'ts',
		'ч'  => 'ch',
		'�?'  => 'sh',
		'щ'  => 'sch',
		'ъ'  => '',
		'ы'  => 'yi',
		'ь'  => '',
		'э'  => 'e',
		'ю'  => 'yu',
		'я'  => 'ya'
	);

	$str = strtr( $str, $utf8 );

	return trim( $str, '-' );
}