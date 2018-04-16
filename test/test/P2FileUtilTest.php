<?php
namespace test;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use app\util\P2FileUtil;

class P2FileUtilTest extends TestCase
{
    public function test_getP2Timestamp()
    {
        $p2Timestamp=P2FileUtil::getP2Timestamp(__DIR__ . DIRECTORY_SEPARATOR . 'artifacts.xml');
        Assert::assertEquals('1518020687959', $p2Timestamp);
    }
}