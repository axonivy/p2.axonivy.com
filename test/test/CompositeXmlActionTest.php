<?php
namespace test;

use PHPUnit\Framework\TestCase;

class CompositeXmlActionTest extends TestCase
{

    public function test_p2Index()
    {
        AppTester::assertThatGet('/p2/8.0/p2.index')
            ->statusCode(200)
            ->contentType('text/plain')
            ->bodyContains('metadata.repository.factory.order=compositeContent.xml,\!');
    }

    public function test_70_compositeArtifacts()
    {
        AppTester::assertThatGet('/p2/8.0/compositeArtifacts.xml')->statusCode(200)
            ->contentType('text/xml')
            ->lastModified('Wed, 07 Feb 2018 16:24:47 UTC')
            ->bodyContains('8.0.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1518020687970'")
            ->bodyContains("<?compositeArtifactRepository version='1.0.0'?>")
            ->bodyContains("<repository name='Axon.ivy 8.0 repository' type='org.eclipse.equinox.internal.p2.artifact.repository.CompositeArtifactRepository' version='1.0.0'>");
    }

    public function test_LE_compositeArtifacts()
    {
        AppTester::assertThatGet('/p2/leading/compositeArtifacts.xml')->statusCode(200)
            ->contentType('text/xml')
            ->lastModified('Wed, 07 Feb 2018 16:24:47 UTC')
            ->bodyContains('7.1.0-201706120950')
            ->bodyContains('7.2.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1518020687960'");
    }
    
    public function test_nightly_compositeArtifacts_additionalLocations()
    {
        AppTester::assertThatGet('/p2/nightly/compositeArtifacts.xml')->statusCode(200)
        	->contentType('text/xml')
        	->lastModified('Sat, 10 Apr 2021 02:11:27 UTC')
        	->bodyContains("'p2.timestamp' value='1618020687959'")
        	->bodyContains("<properties size='3'>")
        	->bodyContains("../features/birt-project-reporting/nightly")
        	->bodyContains("http://download.eclipse.org/eclipse/updates/4.7/")
        	->bodyContains("<?compositeArtifactRepository version='1.0.0'?>")
        	->bodyContains("<repository name='Axon.ivy Nightly Build repository' type='org.eclipse.equinox.internal.p2.artifact.repository.CompositeArtifactRepository' version='1.0.0'>");
    }

    public function test_compositeContent()
    {
        AppTester::assertThatGet('/p2/8.0/compositeContent.xml')->statusCode(200)
            ->contentType('text/xml')
            ->lastModified('Wed, 07 Feb 2018 16:24:47 UTC')
            ->bodyContains('8.0.0-201706120950')
            ->bodyContains("'p2.timestamp' value='1518020687970'")
            ->bodyContains("<?compositeMetadataRepository version='1.0.0'?>")
            ->bodyContains("<repository name='Axon.ivy 8.0 repository' type='org.eclipse.equinox.internal.p2.metadata.repository.CompositeMetadataRepository' version='1.0.0'>");
    }

    public function test_404()
    {
        AppTester::assertThatGet('/p2.index')->statusCode(404);
        AppTester::assertThatGet('/compositeArtifacts.xml')->statusCode(404);
        AppTester::assertThatGet('/compositeContent.xml')->statusCode(404);
        AppTester::assertThatGet('//p2.index')->statusCode(404);
        AppTester::assertThatGet('//compositeArtifacts.xml')->statusCode(404);
        AppTester::assertThatGet('//compositeContent.xml')->statusCode(404);
        
        AppTester::assertThatGet('/p2/p2.index')->statusCode(404);
        AppTester::assertThatGet('/p2/compositeArtifacts.xml')->statusCode(404);
        AppTester::assertThatGet('/p2/compositeContent.xml')->statusCode(404);
        
        AppTester::assertThatGetThrowsNotFoundException('/p2/xyz/p2.index');
        AppTester::assertThatGetThrowsNotFoundException('/p2/xyz/compositeArtifacts.xml');
        AppTester::assertThatGetThrowsNotFoundException('/p2/xyz/compositeContent.xml');
    }
}