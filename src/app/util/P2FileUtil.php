<?php
namespace app\util;

use Slim\Exception\HttpNotFoundException;
use Nelexa\Zip\ZipFile;

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
        $additionalLocationsPath = $rootFolder . '_additional-locations.txt';
        if (file_exists($additionalLocationsPath)) {
            self::appendLines($locations, $additionalLocationsPath);
        }
        usort($locations, fn($a, $b) => version_compare($a, $b));
        $timestamps = array_map(fn ($filename) => self::getP2Timestamp("$rootFolder/$filename"), $locations);
        $timestamp = empty($timestamps) ? 0 : max($timestamps);
        return new Composite($locations, $timestamp);
    }

    public static function getLatestVersion($rootFolder): string
    {
        $locations = self::getLocations($rootFolder);
        $latestVersion = empty($locations) ? 'None' : max($locations);
        return $latestVersion;
    }

    private static function getLocations($rootFolder): array
    {
        $pathNames = glob("$rootFolder/*");
        $directories = array_filter($pathNames, 'is_dir');
        $directories = array_filter($directories, fn ($pathName) => file_exists("$pathName/p2.ready"));
        $locations = array_map(fn ($pathName) => basename($pathName), $directories);
        return $locations;
    }

    private static function appendLines(array &$appendTo, string $filePath)
    {
        if ($file = fopen($filePath, "r")) {
            while (!feof($file)) {
                $line = trim(fgets($file));
                if (!str_starts_with($line, '#') && !empty($line)) {
                    array_push($appendTo, $line);
                }
            }
            fclose($file);
        }
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
        $zipFile->open($zipFilename);
        $zipFile->extractTo($extractToFolder);
        $zipFile->close();
    }

    public static function getP2TimestampFromXml(string $filename): string
    {
        $xml = simplexml_load_file($filename);
        return $xml->xpath("/repository/properties/property[@name='p2.timestamp']/@value")[0];
    }

    public static function getP2ArtifactsFromXml(string $filename): array
    {
        $xml = simplexml_load_file($filename);
        $xmlArtifacts = $xml->xpath("/repository/artifacts/artifact");
        return array_map(fn($xmlArtifact) => self::toArtifact($xmlArtifact), $xmlArtifacts);
    }

    private static function toArtifact($xmlArt)
    {
        $classifier = (string) $xmlArt['classifier'];
        if ($classifier === "osgi.bundle") {
            return new Plugin((string) $xmlArt['id'], (string) $xmlArt['version']);
        } else if ($classifier === "org.eclipse.update.feature") {
            return new Feature((string) $xmlArt['id'], (string) $xmlArt['version']);
        } else if ($classifier === "binary") {
            return new Binary((string) $xmlArt['id'], (string) $xmlArt['version']);
        }
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

abstract class Artifact
{
    public $id;
    public $version;

    public function __construct(string $id, string $version)
    {
        $this->id = $id;
        $this->version = $version;
    }
}

class Feature extends Artifact
{
}
class Plugin extends Artifact
{
}
class Binary extends Artifact
{
}
