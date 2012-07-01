<?php

class Fetcher
{
    // Fetch the contents of a web page.
    
    public static function download($url = null, $timeout = 10, $user_agent = 'PHP')
    {
        if (!function_exists('curl_init'))
        {
            throw new FetcherException('cURL module is not available.');
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}

class FetcherException extends Exception { }
