<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    public function notif($notif,$title,$message,$status)
    {
        $_SESSION['notif'] = $notif;
        $_SESSION['title'] = $title;
        $_SESSION['message'] = $message;
        $_SESSION['status'] = $status;
        return true;
    }
}
