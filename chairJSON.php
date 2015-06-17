<?php

$num = 1;

$url = "http://www.ikea.com/jp/ja/catalog/categories/departments/dining/25219/";

$depth = 0;
$height = 0;
$width = 0;
$href;
$src;
$title;
$price;


$key1 = "奥行き";
$key2 = "高さ";
$key3 = "幅";

require_once("phpQuery-onefile.php");

$html = file_get_contents($url); // get html

$doc = phpQuery::newDocument($html); // phpQuery document object



foreach ($doc[".threeColumn.product"] as $product){

  $title = pq($product) -> find("div.productTitle.floatLeft") -> text();
  $sub = pq($product) -> find("div.productDesp") -> text();
  $title .= " " . $sub;

  $price = pq($product) -> find("div.price.regularPrice") -> text();
  $price = preg_replace('/[^0-9]/', '', $price);

  $link = pq($product) -> find("a");  // find link
  $link -> attr("href", "http://www.ikea.com" . $link -> attr("href") ); // change link url
  $href = $link -> attr("href");

  $img = pq($product) -> find("img");
  $img -> attr("src", "http://www.ikea.com" . $img -> attr("src") ); // change img url
  $src = $img -> attr("src");

  $size =  pq($product) -> find(".size") -> text();
  $sizes = split(",", $size);

  $count = 0;

  foreach ( $sizes as $s ) {
    $s = str_replace( "\t", "", $s);
    $s = str_replace( "\n", "", $s);
    $s = str_replace( " ", "", $s);
    $s = str_replace( "\r", "", $s);
    $s = str_replace( "cm", "", $s);  // remove "cm"

    $value = split(":", $s);

    if ($value[0] === $key1) {
      $depth = $value[1];
      $count++;
    } else if ($value[0] === $key2) {
      $height = $value[1];
      $count++;
    } else if ($value[0] === $key3) {
      $width = $value[1];
      $count++;
    }
  }

  if ($count === 3) {

    $result [] = array( 
      "title" => $title, 
      "price" => (int)$price, 
      "src" => $src,
      "href" => $href,
      "width" => (int)$width,
      "height" => (int)$height,
      "depth" => (int)$depth  
      );

  }

}


header( 'Content-type: application/json' );

echo json_encode($result);

?>
