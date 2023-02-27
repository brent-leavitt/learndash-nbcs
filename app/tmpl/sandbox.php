<?php 

namespace Doula_Course\App\Tmpl;


if ( ! defined( 'ABSPATH' ) ) { exit; }

echo "The Sandbox has been loaded! <br>";

print( "<pre>" ); 

/*
$a = array('<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9");

echo "Normal: ",  maybe_json_encode($a), "\n";
echo "Tags: ",    maybe_json_encode($a, JSON_HEX_TAG), "\n";
echo "Apos: ",    maybe_json_encode($a, JSON_HEX_APOS), "\n";
echo "Quot: ",    maybe_json_encode($a, JSON_HEX_QUOT), "\n";
echo "Amp: ",     maybe_json_encode($a, JSON_HEX_AMP), "\n";
echo "Unicode: ", maybe_json_encode($a, JSON_UNESCAPED_UNICODE), "\n";
echo "All: ",     maybe_json_encode($a, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), "\n\n";

$b = array();

echo "Empty array output as array: ", maybe_json_encode($b), "\n";
echo "Empty array output as object: ", maybe_json_encode($b, JSON_FORCE_OBJECT), "\n\n";

$c = array(array(1,2,3));

echo "Non-associative array output as array: ", maybe_json_encode($c), "\n";
echo "Non-associative array output as object: ", maybe_json_encode($c, JSON_FORCE_OBJECT), "\n\n";

$d = array('foo' => 'bar', 'baz' => 'long');

echo "Associative array always output as object: ", maybe_json_encode($d), "\n";
echo "Associative array always output as object: ", maybe_json_encode($d, JSON_FORCE_OBJECT), "\n\n";
*/

echo "<hr>";

$assoc = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
$assoc1 = array('a' => 'one', 'b' => 'two and', 'c' => 'three', 'd' => 'four and', 'e' => 'five');
$encoded_assoc = ( json_encode( $assoc ) ); 
$encoded_assoc1 = ( json_encode( $assoc1 ) ); 
$maybe_encoded_assoc = ( maybe_json_encode( $assoc ) ); 
$maybe_encoded_assoc1 = ( maybe_json_encode( $assoc1 ) ); 

$maybe_decoded_assoc = ( maybe_json_decode( $maybe_encoded_assoc ) ); 
$maybe_decoded_assoc1 = ( maybe_json_decode( $maybe_encoded_assoc1 ) ); 


echo "<h3>IS_JSON?</h3>";
print( "assoc: ". is_json( $assoc ) ); echo"<br>"; 
print( "assoc1: ". is_json( $assoc1 ) );  echo"<br>"; 
print( "encoded_assoc: ". is_json( $encoded_assoc ) );  echo"<br>"; 
print( "encoded_assoc1: ". is_json( $encoded_assoc1 ) );  echo"<br>"; 
print( "maybe_encoded_assoc: ". is_json( $maybe_encoded_assoc ) );  echo"<br>"; 
print( "maybe_encoded_assoc1: ". is_json( $maybe_encoded_assoc1 ) );  echo"<br>"; 
print( "maybe_decoded_assoc: ". is_json( $maybe_decoded_assoc ) );  echo"<br>"; 
print( "maybe_decoded_assoc1: ". is_json( $maybe_decoded_assoc1 ) );  echo"<br>"; 


echo "<h3>Encoded:</h3>";
var_dump( $encoded_assoc );

echo "<h3>Maybe_Encoded:</h3>";
var_dump( $assoc ); 
var_dump( $maybe_encoded_assoc );

var_dump( $assoc1 ); 
var_dump( $maybe_encoded_assoc1 );

echo "<h3>Maybe_Decoded:</h3>";
var_dump( $maybe_encoded_assoc ); 
var_dump( $maybe_decoded_assoc );

var_dump( $maybe_encoded_assoc1); 
var_dump( $maybe_decoded_assoc1 );


echo "<hr>";



print( "</pre>" ); 
?>	
