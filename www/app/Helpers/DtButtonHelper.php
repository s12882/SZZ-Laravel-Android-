<?php

namespace App\Helpers;

use View;

class DtButtonHelper {

    public static function getView($action, $class, $iconClass, $tooltipText, $text) {
        return View::make('layouts.partial.dt_button', [
              'action' => $action,
              'class' => $class,
              'iconClass' => $iconClass,
              'tooltipText' => $tooltipText,
              'text' => $text
        ]);
    }

    public static function getByType($action, $buttonType) {
        return View::make('layouts.partial.dt_button_type', [
              'action' => $action,
              'buttonType' => $buttonType
        ]);
    }

}
