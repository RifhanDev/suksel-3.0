<?php

namespace App\Libraries;

class Asset
{
   private static $queued = [
    'css' => [],
    'js'  => []
   ];

   public static function push($type, $path) {
      array_push(self::$queued[$type], $path);
   }

   public static function unshift($type, $path) {
      array_unshift(self::$queued[$type], $path);
   }

   public static function tags($type) {
     	$returnString = '';
     	$min = '';
     	foreach (self::$queued[$type] as $path) {
         if($type === 'js') {
            $jsUrl = url('/js/' . $path . $min . '.js');
            $returnString =  $returnString . '      <script src="' . $jsUrl . '"></script>'."\n";
         }
         else {
            $cssUrl = url('/css/' . $path . $min . '.css');
            $returnString = $returnString . '      <link rel="stylesheet" href="' . $cssUrl . '"/>'."\n";
         }
     	}
     	return $returnString;
   }
}