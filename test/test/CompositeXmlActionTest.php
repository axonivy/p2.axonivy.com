<?php
namespace test;

use PHPUnit\Framework\TestCase;

class CompositeXmlActionTest extends TestCase
{

    public function test_p2Index()
    {
        AppTester::assertThatGet('/8.0/p2.index')->statusCode(200)
            ->contentType('text/plain')
            ->bodyContains('metadata.repository.factory.order=compositeContent.xml,\!');
    }

    public function test_70_compositeArtifacts()
    {
        AppTester::assertThatGet('/8.0/compositeArtifacts.xml')->statusCode(200)
            ->contentType('text/xml')
            ->bodyContains('8.0.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1523435622000'")
            ->bodyContains("<?compositeArtifactRepository version='1.0.0'?>")
            ->bodyContains("<repository name='The Axon.ivy repository' type='org.eclipse.equinox.internal.p2.artifact.repository.CompositeArtifactRepository' version='1.0.0'>");
    }

    public function test_LE_compositeArtifacts()
    {
        AppTester::assertThatGet('/LE/compositeArtifacts.xml')->statusCode(200)
            ->contentType('text/xml')
            ->bodyContains('7.1.0-201706120950')
            ->bodyContains('7.2.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1523435631000'");
    }

    public function test_compositeContent()
    {
        AppTester::assertThatGet('/8.0/compositeContent.xml')->statusCode(200)
            ->contentType('text/xml')
            ->bodyContains('8.0.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1523435622000'")
            ->bodyContains("<?compositeMetadataRepository version='1.0.0'?>")
            ->bodyContains("<repository name='The Axon.ivy repository' type='org.eclipse.equinox.internal.p2.metadata.repository.CompositeMetadataRepository' version='1.0.0'>");
    }

    public function test_404()
    {
        AppTester::assertThatGet('/p2.index')->statusCode(404);
        AppTester::assertThatGet('/compositeArtifacts.xml')->statusCode(404);
        AppTester::assertThatGet('/compositeContent.xml')->statusCode(404);
        AppTester::assertThatGet('//p2.index')->statusCode(404);
        AppTester::assertThatGet('//compositeArtifacts.xml')->statusCode(404);
        AppTester::assertThatGet('//compositeContent.xml')->statusCode(404);
        
        AppTester::assertThatGetThrowsNotFoundException('/xyz/p2.index');
        AppTester::assertThatGetThrowsNotFoundException('/xyz/compositeArtifacts.xml');
        AppTester::assertThatGetThrowsNotFoundException('/xyz/compositeContent.xml');
    }
}