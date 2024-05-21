<?php 

namespace App\Helpers;

use Illuminate\Support\Str;


final class NovosHelpers
{

    /** 
     * Format File Name
     * Removes Spaces, all special characters, 
     * @param string $filename
     * @return string
     */
    public static function formatFileName(string $filename): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', Str::slug($filename)));
    }
}