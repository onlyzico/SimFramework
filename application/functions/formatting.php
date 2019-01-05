<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 * @source WordPress
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function parse__str( $string, &$array ) {
  parse_str( $string, $data );

  if ( get_magic_quotes_gpc() )
    $array = stripslashes_deep( $array );
}

function stripslashes_deep( $value ) {
  return map_deep( $value, 'stripslashes_from_strings_only' );
}

function map_deep( $value, $callback ) {
  if ( is_array( $value ) ) {
    foreach ( $value as $index => $item )
      $value[$index] = map_deep( $item, $callback );
  } elseif ( is_object( $value ) ) {
    $object_vars = get_object_vars( $value );
    foreach ( $object_vars as $property_name => $property_value )
      $value->$property_name = map_deep( $property_value, $callback );
  } else {
    $value = call_user_func( $callback, $value );
  }

  return $value;
}

function stripslashes_from_strings_only( $value ) {
  return is_string( $value ) ? stripslashes( $value ) : $value;
}

function autop( $pee, $br = true ) {
	$pre_tags = [];

	if ( trim( $pee ) === '' )
		return '';

	$pee = $pee . "\n";

	if ( strpos( $pee, '<pre' ) !== false ) {
		$pee_parts = explode( '</pre>', $pee );
		$last_pee = array_pop( $pee_parts );
		$pee = '';
		$i = 0;

		foreach ( $pee_parts as $pee_part ) {
			$start = strpos( $pee_part, '<pre' );

			if ( $start === false ) {
				$pee.= $pee_part;
				continue;
			}

			$name = "<pre pre-tag-$i></pre>";
			$pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

			$pee.= substr( $pee_part, 0, $start ) . $name;
			$i++;
		}

		$pee.= $last_pee;
	}

	$pee = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee );
	$all_blocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
	$pee = preg_replace( '!(<' . $all_blocks . '[\s/>])!', "\n\n$1", $pee );
	$pee = preg_replace( '!(</' . $all_blocks . '>)!', "$1\n\n", $pee );
	$pee = str_replace( [ "\r\n", "\r" ], "\n", $pee );
	$pee = replace_in_html_tags( $pee, array( "\n" => " <!-- wpnl --> " ) );

	if ( strpos( $pee, '<option' ) !== false ) {
		$pee = preg_replace( '|\s*<option|', '<option', $pee );
		$pee = preg_replace( '|</option>\s*|', '</option>', $pee );
	}

	if ( strpos( $pee, '</object>' ) !== false ) {
		$pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
		$pee = preg_replace( '|\s*</object>|', '</object>', $pee );
		$pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
	}

	if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
		$pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
		$pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
		$pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
	}

	if ( strpos( $pee, '<figcaption' ) !== false ) {
		$pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
		$pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
	}

	$pee = preg_replace( "/\n\n+/", "\n\n", $pee );
	$pees = preg_split( '/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY );
	$pee = '';

	foreach ( $pees as $tinkle )
		$pee.= '<p>' . trim( $tinkle, "\n" ) . "</p>\n";

	$pee = preg_replace( '|<p>\s*</p>|', '', $pee );
	$pee = preg_replace( '!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee );
	$pee = preg_replace( '!<p>\s*(</?' . $all_blocks . '[^>]*>)\s*</p>!', "$1", $pee );
	$pee = preg_replace( "|<p>(<li.+?)</p>|", "$1", $pee );
	$pee = preg_replace( '|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee );
	$pee = str_replace( '</blockquote></p>', '</p></blockquote>', $pee );
	$pee = preg_replace( '!<p>\s*(</?' . $all_blocks . '[^>]*>)!', "$1", $pee );
	$pee = preg_replace( '!(</?' . $all_blocks . '[^>]*>)\s*</p>!', "$1", $pee );

	if ( $br ) {
		$pee = preg_replace_callback( '/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee );
		$pee = str_replace( [ '<br>', '<br/>' ], '<br />', $pee );
		$pee = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $pee );
		$pee = str_replace( '<WPPreserveNewline />', "\n", $pee );
	}

	$pee = preg_replace( '!(</?' . $all_blocks . '[^>]*>)\s*<br />!', "$1", $pee );
	$pee = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee );
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );

	if ( ! empty( $pre_tags ) ) {
		$pee = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $pee );
  } if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
		$pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
  }

	return $pee;
}

function replace_in_html_tags( $haystack, $replace_pairs ) {
	$textarr = html_split( $haystack );
	$changed = false;

	if ( 1 === count( $replace_pairs ) ) {
		foreach ( $replace_pairs as $needle => $replace );

		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			if ( false !== strpos( $textarr[$i], $needle ) ) {
				$textarr[$i] = str_replace( $needle, $replace, $textarr[$i] );
				$changed = true;
			}
		}
	} else {
		$needles = array_keys( $replace_pairs );
		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			foreach ( $needles as $needle ) {
				if ( false !== strpos( $textarr[$i], $needle ) ) {
					$textarr[$i] = strtr( $textarr[$i], $replace_pairs );
					$changed = true;

					break;
				}
			}
		}
	}

	if ( $changed )
		$haystack = implode( $textarr );

	return $haystack;
}

function html_split( $input ) {
	return preg_split( html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}

function html_split_regex() {
	static $regex;

	if ( ! isset( $regex ) ) {
		$comments = '!(?:-(?!->)[^\-]*+)*+(?:-->)?';
		$cdata = '!\[CDATA\[[^\]]*+(?:](?!]>)[^\]]*+)*+(?:]]>)?';
		$escaped = '(?=!--|!\[CDATA\[)(?(?=!-)' . $comments . '|' . $cdata . ')';
		$regex = '/(<(?' . $escaped . '|[^>]*>?))/';
	}

	return $regex;
}

function _autop_newline_preservation_helper( $matches ) {
	return str_replace( "\n", "<WPPreserveNewline />", $matches[0] );
}

function sanitize_title( $string ) {
	if ( ! preg_match( '/[\x80-\xff]/', $string ) )
		return $string;

	if ( seems_utf8( $string ) ) {
		$chars = [
  		'ª' => 'a', 'º' => 'o',
  		'À' => 'A', 'Á' => 'A',
  		'Â' => 'A', 'Ã' => 'A',
  		'Ä' => 'A', 'Å' => 'A',
  		'Æ' => 'AE','Ç' => 'C',
  		'È' => 'E', 'É' => 'E',
  		'Ê' => 'E', 'Ë' => 'E',
  		'Ì' => 'I', 'Í' => 'I',
  		'Î' => 'I', 'Ï' => 'I',
  		'Ð' => 'D', 'Ñ' => 'N',
  		'Ò' => 'O', 'Ó' => 'O',
  		'Ô' => 'O', 'Õ' => 'O',
  		'Ö' => 'O', 'Ù' => 'U',
  		'Ú' => 'U', 'Û' => 'U',
  		'Ü' => 'U', 'Ý' => 'Y',
  		'Þ' => 'TH','ß' => 's',
  		'à' => 'a', 'á' => 'a',
  		'â' => 'a', 'ã' => 'a',
  		'ä' => 'a', 'å' => 'a',
  		'æ' => 'ae','ç' => 'c',
  		'è' => 'e', 'é' => 'e',
  		'ê' => 'e', 'ë' => 'e',
  		'ì' => 'i', 'í' => 'i',
  		'î' => 'i', 'ï' => 'i',
  		'ð' => 'd', 'ñ' => 'n',
  		'ò' => 'o', 'ó' => 'o',
  		'ô' => 'o', 'õ' => 'o',
  		'ö' => 'o', 'ø' => 'o',
  		'ù' => 'u', 'ú' => 'u',
  		'û' => 'u', 'ü' => 'u',
  		'ý' => 'y', 'þ' => 'th',
  		'ÿ' => 'y', 'Ø' => 'O',
  		'Ā' => 'A', 'ā' => 'a',
  		'Ă' => 'A', 'ă' => 'a',
  		'Ą' => 'A', 'ą' => 'a',
  		'Ć' => 'C', 'ć' => 'c',
  		'Ĉ' => 'C', 'ĉ' => 'c',
  		'Ċ' => 'C', 'ċ' => 'c',
  		'Č' => 'C', 'č' => 'c',
  		'Ď' => 'D', 'ď' => 'd',
  		'Đ' => 'D', 'đ' => 'd',
  		'Ē' => 'E', 'ē' => 'e',
  		'Ĕ' => 'E', 'ĕ' => 'e',
  		'Ė' => 'E', 'ė' => 'e',
  		'Ę' => 'E', 'ę' => 'e',
  		'Ě' => 'E', 'ě' => 'e',
  		'Ĝ' => 'G', 'ĝ' => 'g',
  		'Ğ' => 'G', 'ğ' => 'g',
  		'Ġ' => 'G', 'ġ' => 'g',
  		'Ģ' => 'G', 'ģ' => 'g',
  		'Ĥ' => 'H', 'ĥ' => 'h',
  		'Ħ' => 'H', 'ħ' => 'h',
  		'Ĩ' => 'I', 'ĩ' => 'i',
  		'Ī' => 'I', 'ī' => 'i',
  		'Ĭ' => 'I', 'ĭ' => 'i',
  		'Į' => 'I', 'į' => 'i',
  		'İ' => 'I', 'ı' => 'i',
  		'Ĳ' => 'IJ','ĳ' => 'ij',
  		'Ĵ' => 'J', 'ĵ' => 'j',
  		'Ķ' => 'K', 'ķ' => 'k',
  		'ĸ' => 'k', 'Ĺ' => 'L',
  		'ĺ' => 'l', 'Ļ' => 'L',
  		'ļ' => 'l', 'Ľ' => 'L',
  		'ľ' => 'l', 'Ŀ' => 'L',
  		'ŀ' => 'l', 'Ł' => 'L',
  		'ł' => 'l', 'Ń' => 'N',
  		'ń' => 'n', 'Ņ' => 'N',
  		'ņ' => 'n', 'Ň' => 'N',
  		'ň' => 'n', 'ŉ' => 'n',
  		'Ŋ' => 'N', 'ŋ' => 'n',
  		'Ō' => 'O', 'ō' => 'o',
  		'Ŏ' => 'O', 'ŏ' => 'o',
  		'Ő' => 'O', 'ő' => 'o',
  		'Œ' => 'OE','œ' => 'oe',
  		'Ŕ' => 'R','ŕ' => 'r',
  		'Ŗ' => 'R','ŗ' => 'r',
  		'Ř' => 'R','ř' => 'r',
  		'Ś' => 'S','ś' => 's',
  		'Ŝ' => 'S','ŝ' => 's',
  		'Ş' => 'S','ş' => 's',
  		'Š' => 'S', 'š' => 's',
  		'Ţ' => 'T', 'ţ' => 't',
  		'Ť' => 'T', 'ť' => 't',
  		'Ŧ' => 'T', 'ŧ' => 't',
  		'Ũ' => 'U', 'ũ' => 'u',
  		'Ū' => 'U', 'ū' => 'u',
  		'Ŭ' => 'U', 'ŭ' => 'u',
  		'Ů' => 'U', 'ů' => 'u',
  		'Ű' => 'U', 'ű' => 'u',
  		'Ų' => 'U', 'ų' => 'u',
  		'Ŵ' => 'W', 'ŵ' => 'w',
  		'Ŷ' => 'Y', 'ŷ' => 'y',
  		'Ÿ' => 'Y', 'Ź' => 'Z',
  		'ź' => 'z', 'Ż' => 'Z',
  		'ż' => 'z', 'Ž' => 'Z',
  		'ž' => 'z', 'ſ' => 's',
  		'Ș' => 'S', 'ș' => 's',
  		'Ț' => 'T', 'ț' => 't',
  		'€' => 'E',
  		'£' => '',
  		'Ơ' => 'O', 'ơ' => 'o',
  		'Ư' => 'U', 'ư' => 'u',
  		'Ầ' => 'A', 'ầ' => 'a',
  		'Ằ' => 'A', 'ằ' => 'a',
  		'Ề' => 'E', 'ề' => 'e',
  		'Ồ' => 'O', 'ồ' => 'o',
  		'Ờ' => 'O', 'ờ' => 'o',
  		'Ừ' => 'U', 'ừ' => 'u',
  		'Ỳ' => 'Y', 'ỳ' => 'y',
  		'Ả' => 'A', 'ả' => 'a',
  		'Ẩ' => 'A', 'ẩ' => 'a',
  		'Ẳ' => 'A', 'ẳ' => 'a',
  		'Ẻ' => 'E', 'ẻ' => 'e',
  		'Ể' => 'E', 'ể' => 'e',
  		'Ỉ' => 'I', 'ỉ' => 'i',
  		'Ỏ' => 'O', 'ỏ' => 'o',
  		'Ổ' => 'O', 'ổ' => 'o',
  		'Ở' => 'O', 'ở' => 'o',
  		'Ủ' => 'U', 'ủ' => 'u',
  		'Ử' => 'U', 'ử' => 'u',
  		'Ỷ' => 'Y', 'ỷ' => 'y',
  		'Ẫ' => 'A', 'ẫ' => 'a',
  		'Ẵ' => 'A', 'ẵ' => 'a',
  		'Ẽ' => 'E', 'ẽ' => 'e',
  		'Ễ' => 'E', 'ễ' => 'e',
  		'Ỗ' => 'O', 'ỗ' => 'o',
  		'Ỡ' => 'O', 'ỡ' => 'o',
  		'Ữ' => 'U', 'ữ' => 'u',
  		'Ỹ' => 'Y', 'ỹ' => 'y',
  		'Ấ' => 'A', 'ấ' => 'a',
  		'Ắ' => 'A', 'ắ' => 'a',
  		'Ế' => 'E', 'ế' => 'e',
  		'Ố' => 'O', 'ố' => 'o',
  		'Ớ' => 'O', 'ớ' => 'o',
  		'Ứ' => 'U', 'ứ' => 'u',
  		'Ạ' => 'A', 'ạ' => 'a',
  		'Ậ' => 'A', 'ậ' => 'a',
  		'Ặ' => 'A', 'ặ' => 'a',
  		'Ẹ' => 'E', 'ẹ' => 'e',
  		'Ệ' => 'E', 'ệ' => 'e',
  		'Ị' => 'I', 'ị' => 'i',
  		'Ọ' => 'O', 'ọ' => 'o',
  		'Ộ' => 'O', 'ộ' => 'o',
  		'Ợ' => 'O', 'ợ' => 'o',
  		'Ụ' => 'U', 'ụ' => 'u',
  		'Ự' => 'U', 'ự' => 'u',
  		'Ỵ' => 'Y', 'ỵ' => 'y',
  		'ɑ' => 'a',
  		'Ǖ' => 'U', 'ǖ' => 'u',
  		'Ǘ' => 'U', 'ǘ' => 'u',
  		'Ǎ' => 'A', 'ǎ' => 'a',
  		'Ǐ' => 'I', 'ǐ' => 'i',
  		'Ǒ' => 'O', 'ǒ' => 'o',
  		'Ǔ' => 'U', 'ǔ' => 'u',
  		'Ǚ' => 'U', 'ǚ' => 'u',
  		'Ǜ' => 'U', 'ǜ' => 'u',
		];

		$string = strtr( $string, $chars );
	} else {
		$chars = [];
		$chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
			."\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
			."\xc3\xc4\xc5\xc7\xc8\xc9\xca"
			."\xcb\xcc\xcd\xce\xcf\xd1\xd2"
			."\xd3\xd4\xd5\xd6\xd8\xd9\xda"
			."\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
			."\xe4\xe5\xe7\xe8\xe9\xea\xeb"
			."\xec\xed\xee\xef\xf1\xf2\xf3"
			."\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
			."\xfc\xfd\xff";
		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr( $string, $chars['in'], $chars['out'] );
		$double_chars = [];
		$double_chars['in'] = [ "\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe" ];
		$double_chars['out'] = [ 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' ];
		$string = str_replace( $double_chars['in'], $double_chars['out'], $string );
	}

	return $string;
}

function seems_utf8( $str ) {
	mbstring_binary_safe_encoding();

  $length = strlen( $str );

  reset_mbstring_encoding();

	for ( $i = 0; $i < $length; $i++ ) {
		$c = ord( $str[$i] );

    if ( $c < 0x80 ) $n = 0;
		elseif ( ( $c & 0xE0 ) == 0xC0 ) $n = 1;
		elseif ( ( $c & 0xF0 ) == 0xE0 ) $n = 2;
		elseif ( ( $c & 0xF8 ) == 0xF0 ) $n = 3;
		elseif ( ( $c & 0xFC ) == 0xF8 ) $n = 4;
		elseif ( ( $c & 0xFE ) == 0xFC ) $n = 5;
		else return false;

    for ( $j = 0; $j < $n; $j++ )
			if ( ( ++$i == $length ) || ( ( ord( $str[$i] ) & 0xC0 ) != 0x80 ) )
				return false;
	}

	return true;
}

function mbstring_binary_safe_encoding( $reset = false ) {
  static $encodings = [];
  static $overloaded = null;

  if ( is_null( $overloaded ) )
    $overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );

  if ( false === $overloaded )
    return;

  if ( ! $reset ) {
    $encoding = mb_internal_encoding();
    array_push( $encodings, $encoding );
    mb_internal_encoding( 'ISO-8859-1' );
  }

  if ( $reset && $encodings ) {
    $encoding = array_pop( $encodings );
    mb_internal_encoding( $encoding );
  }
}

function reset_mbstring_encoding() {
  mbstring_binary_safe_encoding( true );
}
