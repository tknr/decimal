<?php
$src = '';
$raw_number = '';
$dst = '';
$src_dec = 10;
$dst_dec = 10;
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	$src = trim( $_POST['src'] );
	$src_dec = trim( $_POST['src_dec'] );
	$dst_dec = trim( $_POST['dst_dec'] );
	$raw_number = Util::decode( $src, $src_dec );
	$dst = Util::encode( $raw_number, $dst_dec );
}

class Util {

	/**
	 */
	static function full_char_for_decimal() {

		return array_merge( range( '0', '9' ), range( 'A', 'Z' ), range( 'a', 'z' ) );
	
	}

	/**
	 *
	 * @param unknown $number        	
	 * @param number $decimal_number        	
	 * @return boolean|number|string
	 */
	static function encode( $number, $decimal_number = 10 ) {

		if ( count( self::full_char_for_decimal() ) < $decimal_number ) {
			return false;
		}
		$char = array_slice( self::full_char_for_decimal(), 0, $decimal_number, true );
		$result = "";
		$base = count( $char );
		
		while ( $number > 0 ) {
			$result = $char[fmod( $number, $base )] . $result;
			$number = floor( $number / $base );
		}
		return ( $result == "" ) ? 0 : $result;
	
	}

	/**
	 *
	 * @param string $str        	
	 * @param number $decimal_number        	
	 * @return boolean|number
	 */
	static function decode( $str, $decimal_number = 10 ) {

		if ( count( self::full_char_for_decimal() ) < $decimal_number ) {
			return false;
		}
		$char = array_slice( self::full_char_for_decimal(), 0, $decimal_number, true );
		$result = 0;
		$base = count( $char );
		$table = array_flip( $char );
		$digit = array_reverse( preg_split( '//', $str, - 1, PREG_SPLIT_NO_EMPTY ) );
		
		foreach ( $digit as $i => $value ) {
			if ( ! isset( $table[$value] ) )
				return false;
			$result += pow( $base, $i ) * $table[$value];
		}
		
		return $result;
	
	}

}
?>
<html>
<head>
<title>n進数変換テスト</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=Shift_JIS">
</head>
<body>
<form method="post" accept-charset="UTF-8 Shift_JIS" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<br />src:<input type="text" name="src_dec" value="<?php echo $src_dec ; ?>" />進数の<input type="text" name="src" value="<?php echo $src ; ?>" />
<br />dst:<input type="text" name="dst_dec" value="<?php echo $dst_dec ; ?>" />進数の<?php echo $dst ; ?>
<br />10進数での値:<?php echo $raw_number ; ?>
<br /><input type="submit" name="submit" value="convert" />
</form>

</body>
</html>
