<?php
namespace app\util;

use Slim\Exception\HttpNotFoundException;
use PhpZip\ZipFile;

class P2FileUtil
{
    public static function getRootFolder($request, $response, string $p2DataPath, string $version): string
    {
        $rootFolder = "$p2DataPath/$version";
        if (!str_starts_with($rootFolder, $p2DataPath)) {
            throw new HttpNotFoundException($request);
        }
        if (!file_exists($rootFolder)) {
            throw new HttpNotFoundException($request);
        }
        return $rootFolder;
    }

    public static function getFolders($rootFolder): Composite
    {        
        $locations = self::getLocations($rootFolder);        
       
        $version = basename($rootFolder); // e.g. 8.0
        $pathNames = glob("$rootFolder/../features/*");
        $directories = array_filter($pathNames, 'is_dir');
        foreach ($directories as $dir) {
            $versionDir = "$dir/$version";
            if (file_exists($versionDir)) {                
                $featureName = basename($dir);
                $locations[] = "../features/$featureName/$version"; // e.g. ../features/birt-project-reporting/nightly
            }
        }
        $timestamps = array_map(fn ($filename) => self::getP2Timestamp("$rootFolder/$filename"), $locations);
        $timestamp = empty($timestamps) ? 0 : max($timestamps);
        return new Composite($locations, $timestamp);
    }

    private static function getLocations($rootFolder): array
    {
        $pathNames = glob("$rootFolder/*");
        $directories = array_filter($pathNames, 'is_dir');
        $directories = array_filter($directories, fn ($pathName) => file_exists("$pathName/p2.ready"));        
        $locations = array_map(fn ($pathName) => basename($pathName), $directories);
        usort($locations, fn($a, $b) => version_compare($b, $a));
        return $locations;
    }

    public static function getRootFolders($rootFolder): array
    {
        $pathNames = glob("$rootFolder/*");        
        $directories = array_filter($pathNames, 'is_dir');
        return $directories;
    }

    public static function getP2Timestamp(string $p2Folder): string
    {
        $artifactsXml = "$p2Folder/artifacts.xml";
        if (file_exists($artifactsXml)) {
            return self::getP2TimestampFromXml($artifactsXml);
        }

        $artifactsJar = "$p2Folder/artifacts.jar";
        if (file_exists($artifactsJar)) {
            self::unzip($artifactsJar, $p2Folder);
            return self::getP2TimestampFromXml($artifactsXml);
        }
        return 0;
    }

    public static function unzip(string $zipFilename, string $extractToFolder)
    {
        $zipFile = new ZipFile();
        $zipFile->openFile($zipFilename);
        $zipFile->extractTo($extractToFolder);
        $zipFile->close();
    }

    public static function getP2TimestampFromXml(string $filename): string
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
