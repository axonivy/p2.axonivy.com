<?php
namespace app\util;

use JBZoo\Utils\Str;
use Slim\Exception\NotFoundException;

class P2FileUtil
{

    public static function getRootFolder($request, $response, string $p2DataPath, string $version): string
    {
        $rootFolder = $p2DataPath . DIRECTORY_SEPARATOR . $version;
        
        if (! Str::isStart($rootFolder, $p2DataPath)) {
            throw new NotFoundException($request, $response);
        }
        if (! file_exists($rootFolder)) {
            throw new NotFoundException($request, $response);
        }
        return $rootFolder;
    }

    public static function getFolders($rootFolder): Composite
    {
        $pathNames = glob($rootFolder . DIRECTORY_SEPARATOR . '*');
        $directories = array_filter($pathNames, 'is_dir');
        $directories = array_filter($directories, function ($pathName) {
            return file_exists($pathName . DIRECTORY_SEPARATOR . 'p2.complete');
        });
        
        $locations = array_map(function ($pathName) {
            return basename($pathName);
        }, $directories);
        
        $additionalLocationsPath = $rootFolder . '_additional-locations.txt';
        if (file_exists($additionalLocationsPath)) {
            self::appendLines($locations, $additionalLocationsPath);
        }
        
        $timestamps = array_map(function ($filename) use ($rootFolder) {
            $filepath = $rootFolder . DIRECTORY_SEPARATOR . $filename . DIRECTORY_SEPARATOR . 'artifacts.xml';
            return file_exists($filepath) ? self::getP2Timestamp($filepath) : 0;
        }, $locations);
        
        $timestamp = empty($timestamps) ? 0 : max($timestamps);
        
        return new Composite($locations, $timestamp);
    }

    private static function appendLines(array &$appendTo, string $filePath)
    {
        if ($file = fopen($filePath, "r")) {
            while (! feof($file)) {
                $line = Str::trim(fgets($file));
                if (! Str::isStart($line, '#') && ! empty($line)) {
                    array_push($appendTo, $line);
                }
            }
            fclose($file);
        }
    }

    public static function getP2Timestamp(string $filename): string
    {
        $xml = simplexml_load_file($filename);
        return $xml->xpath("/repository/properties/property[@name='p2.timestamp']/@value")[0];
    }
}

class Composite
{
    public $locations;
    public $timestamp;

    public function __construct(array $locations, int $timestamp)
    {
        $this->locations = $locations;
        $this->timestamp = $timestamp;
    }
}