<?php
namespace App\Services;

class AlertService
{
    public static function updated($message = null){
        return [
            notyf()->success($message ? $message : 'Updated successfully.')
        ];
    }

    public static function deleted($message = null){
        return [
            notyf()->success($message ? $message : 'Deleted successfully.')
        ];
    }

    public static function created($message = null){
        return [
            notyf()->success($message ? $message : 'Created successfully.')
        ];
    }

    public static function error($message = null){
        return [
            notyf()->error($message ? $message : 'An error occurred.')
        ];
    }
}

?>
