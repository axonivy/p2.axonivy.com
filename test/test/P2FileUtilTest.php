<?php
namespace test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use app\util\P2FileUtil;
use app\util\Plugin;
use app\util\Binary;

class P2FileUtilTest extends TestCase
{
    public function test_getP2Timestamp_withJar()
    {
        try {
            $artifactsFolder = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'artifacts', 'jar']);
            $p2Timestamp=P2FileUtil::getP2Timestamp($artifactsFolder);
            Assert::assertEquals('1518020687959', $p2Timestamp);
            
            $artifactsXml = $artifactsFolder . DIRECTORY_SEPARATOR . 'artifacts.xml';
            $p2Timestamp = P2FileUtil::getP2TimestampFromXml($artifactsXml);
            Assert::assertEquals('1518020687959', $p2Timestamp);
        } finally {
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
    
    public function test_getP2ArtifactsFromXml()
    {
        $artifactsXml = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'artifacts', 'xml', 'artifacts.xml']);
        $artifacts=P2FileUtil::getP2ArtifactsFromXml($artifactsXml);
        Assert::assertEquals(array(
            new Plugin("javax.annotation", "1.2.0.v201602091430"),
            new Plugin("org.eclipse.ant.launching", "1.2.600.v20190701-1953"),
            new Binary("ch.ivyteam.ivy.designer.feature_root", "8.0.0.201911250142")
        ), $artifacts);
    }
}