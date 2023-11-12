<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

if (!function_exists('convertUtf8')) {
    function convertUtf8($value)
    {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }
}

if (!function_exists('createSlug')) {
    function createSlug($string): array|false|string|null
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace('/', '', $slug);
        $slug = str_replace('?', '', $slug);
        $slug = str_replace(',', '', $slug);

        return mb_strtolower($slug);
    }
}

if (!function_exists('replaceBaseUrl')) {
    function replaceBaseUrl($html, $type)
    {
        $startDelimiter = 'src=""';
        if ($type == 'summernote') {
            $endDelimiter = '/img/summernote';
        } elseif ($type == 'pagebuilder') {
            $endDelimiter = '/img';
        }

        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = 0;

        while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($html, $endDelimiter, $contentStart);

            if (false === $contentEnd) {
                break;
            }

            $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $html;
    }
}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue(array $values): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "$envKey=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "$envKey=$envValue\n";
                } else {
                    $str = str_replace($oldLine, "$envKey=$envValue", $str);
                }
            }
        }

        $str = substr($str, 0, -1);

        if (!file_put_contents($envFile, $str)) return false;

        return true;
    }
}


if (!function_exists('get_href')) {
    function get_href($data)
    {

        if ($data->type == 'home') {
            $link_href = route('index');
        } else if ($data->type == 'courses') {
            $link_href = route('courses');
        } else if ($data->type == 'instructors') {
            $link_href = route('instructors');
        } else if ($data->type == 'blog') {
            $link_href = route('blogs');
        } else if ($data->type == 'faq') {
            $link_href = route('faqs');
        } else if ($data->type == 'contact') {
            $link_href = route('contact');
        } else if ($data->type == 'custom') {
            /**
             * this menu has created using menu-builder from the admin panel.
             * this menu will be used as drop-down or link any outside url to this system.
             */
            if ($data->href == '') {
                $link_href = '#';
            } else {
                $link_href = $data->href;
            }
        } else {
            // this menu is for the custom page which has created from the admin panel.
            $link_href = route('dynamic_page', ['slug' => $data->type]);
        }

        return $link_href;
    }


}


if (!function_exists("remoteAsset")) {
    function remoteAsset($path): string
    {
        $path = 'assets' . $path;
        return Storage::temporaryUrl($path, Carbon::today()->addDay());
    }
}

