<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;

class FileController extends Controller
{
    public static function readCsv($fileName) {
        $exists = Storage::disk('public')->exists($fileName);
        if (!$exists) Storage::disk('public')->put($fileName, '');

        $content = [];
        $filePath = public_path('storage/' . $fileName);
        $fRead = fopen($filePath, "r");
        while (($data = fgetcsv($fRead, ",")) !== FALSE) {
            $splitWord = [];
            foreach ($data as $word){
                if (count(explode(",", $word)) == 1) $splitWord[] = $word;
                else {
                    foreach(explode(",", $word) as $term) {
                        $splitWord[] = $term;
                    }
                }
            }
            $content[] = $splitWord;
        }
        fclose($fRead);
        return $content;
    }
    
    public static function writeCsv($fileName, $content) {
        $exists = Storage::disk('public')->exists($fileName);
        if (!$exists) Storage::disk('public')->put($fileName, '');

        $filePath = public_path('storage/' . $fileName);
        $fWrite = fopen($filePath, "w");
        foreach ($content as $line) {
            fputcsv($fWrite, str_replace("\r\n",'', $line), ',');
            // fputcsv($fWrite, $line, ',');
        }
        fclose($fWrite);
    }
}
