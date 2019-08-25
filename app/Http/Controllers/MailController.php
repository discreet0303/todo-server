<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\FileController;

use Storage;
use Mail;

class MailController extends Controller
{
    public function sendMailWithList() {
        set_time_limit(0);
 
        $mailListName = 'mailList.csv';
        $mailList = FileController::readCsv($mailListName);
        
        foreach($mailList as $key => $mail) {
            if ($mail[2] == 1) continue;

            $isSuccess = self::sendMailWithFile([
                'email' => $mail[0],
                'fileName' => $mail[1],
            ]);
            if ($isSuccess) $mailList[$key][2] = 1;
            FileController::writeCsv($mailListName, $mailList);
        }
        
        return response()->json($mailList, 200);
    }

    public static function sendMailWithFile($mailInfo) {
        // return $mailInfo;
        try {
            Mail::send('mail.Template', [], function($message) use($mailInfo){
                $message->to($mailInfo['email'])->subject('title');
                $message->attach(public_path('file/' . $mailInfo['fileName']));
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
