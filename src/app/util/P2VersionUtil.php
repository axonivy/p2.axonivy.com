<?php
namespace app\util;

class P2VersionUtil
{
    public static function toLongversionString(string $version): string
    {
        switch ($version) {
            case 'leading':
                return 'Leading Edge';
            case 'sprint':
                return 'Sprint Release';
            case 'nightly':
                return 'Nightly Build';
        }
        if (substr($version, 0, 4) == 'lts-') {
            return 'Long Term Supported ' . substr($version, 4);
        }
        return $version;
    }
}
