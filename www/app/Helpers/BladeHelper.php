<?php
namespace App\Helpers;

class BladeHelper
{
    /**
     * Dodaje do odnośników w layoucie klasę active, w zależności od strony na której użytkownik się znajduje)
     * @param $route
     * @return string
     */
    public static function isRouteSelected($route){
        return (\Request::is($route.'/*') || \Request::is($route)) ? "active" : '';
    }
}
