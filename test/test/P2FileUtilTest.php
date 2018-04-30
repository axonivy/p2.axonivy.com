<?php
namespace test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use app\util\P2FileUtil;

class P2FileUtilTest extends TestCase
{
    
    public function test_getP2Timestamp_withJar()
    {
        try {
            
            $artifactsFolder = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'artifacts', 'jar']);
            $p2Timestamp=P2FileUtil::getP2Timestamp($artifactsFolder);
            Assert::assertEquals('1518020687959', $p2Timestamp);
            
            $artifactsXml = $artifactsFolder . DIRECTORY_SEPARATOR . 'artifacts.xml';
            $p2Timestamp=P2FileUtil::getP2TimestampFromXml($artifactsXml);
            Assert::assertEquals('1518020687959', $p2Timestamp);
        }
        finally {
            unlink($artifactsXml);
        }
    }
    
    public function test_getP2Timestamp_withXml()
    {
        $artifactsFolder = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'artifacts', 'xml']);
        $p2Timestamp=P2FileUtil::getP2Timestamp($artifactsFolder);
        Assert::assertEquals('1518020687959', $p2Timestamp);
    }
    
    public function test_getP2TimestampFromXml()
    {
        $artifactsXml = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'artifacts', 'xml', 'artifacts.xml']);
        $p2Timestamp=P2FileUtil::getP2TimestampFromXml($artifactsXml);
        Assert::assertEquals('1518020687959', $p2Timestamp);
    }
}