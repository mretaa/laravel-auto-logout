<?php

namespace Mretaa\AutoLogout;

use Illuminate\Support\Facades\Blade;

class AutoLogoutDirective
{
    public static function register()
    {
        Blade::directive('autoLogout', function () {
            return "<?php echo view('dialog.auto-logout-modal'); ?>\n<script src='" . asset('vendor/auto-logout/js/auto-logout.js') . "'></script>\n";
        });
    }
}