<?php
namespace test;

use PHPUnit\Framework\Assert;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\RequestFactory;
use app\Website;

class AppTester
{
    private $response;
    
    private function __construct(Response $response)
    {
        $this->response = $response;
    }
    
    public static function assertThatGet(string $url): AppTester
    {
        return new AppTester(self::get($url));
    }
    
    private static function get(string $url): Response
    {
        $app = Website::createApp('test/p2-data');
        $request = (new RequestFactory())->createRequest('GET', $url);
        return $app->handle($request);
    }
    
    public function bodyDoesNotContain(string $stringNotContain): AppTester
    {
        $body = $this->response->getBody();
        $body->rewind();
        $content = $body->getContents();
        Assert::assertStringNotContainsStringIgnoringCase($stringNotContain, $content);
        return $this;
    }
    
    public function bodyContains(string $expectedToContain): AppTester
    {
        $body = $this->response->getBody();
        $body->rewind();
        $content = $body->getContents();
        Assert::assertStringContainsString($expectedToContain, $content);
        return $this;
    }
    
    public function getBody(): string
    {
        $body = $this->response->getBody();
        $body->rewind();
        $content = $body->getContents();
        return $content;
    }
    
    public function statusCode(int $expectedStatusCode): AppTester
    {
        Assert::assertEquals($expectedStatusCode, $this->response->getStatusCode());
        return $this;
    }
    
    public function ok(): AppTester
    {
        self::statusCode(200);
        return $this;
    }
    
    public function notFound(): AppTester
    {
        self::statusCode(404);
        return $this;
    }
    
    public function contentType(string $expectedContentType): AppTester
    {
        Assert::assertEquals($expectedContentType, $this->response->getHeader('Content-Type')[0]);
        return $this;
    }
    
    public function lastModified(string $expectedLastModified): AppTester
    {
        Assert::assertEquals($expectedLastModified, $this->response->getHeader('Last-Modified')[0]);
        return $this;
    }
}
