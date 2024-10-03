<?php
declare(strict_types=1);
namespace Tests\Utils\FileLoader;

use App\Utils\FileLoader\JsonFileLoader;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class JsonFileLoaderTest extends TestCase
{
    protected function setUp(): void
    {
        // Creating a temporary valid JSON file for the test
        file_put_contents('test_valid.json', '{"name": "Test"}');
        // Creating a temporary invalid JSON file for the test
        file_put_contents('test_invalid.json', 'invalid_json');
    }

    protected function tearDown(): void
    {
        // Clean up the temporary files after tests
        unlink('test_valid.json');
        unlink('test_invalid.json');
    }

    public function testLoadValidJson()
    {
        $jsonLoader = $this->getMockBuilder(JsonFileLoader::class)
                           ->onlyMethods(['parse']) // We mock the parse method, not file_get_contents
                           ->getMock();

        // Test valid JSON parsing
        $jsonLoader->expects($this->once())
                   ->method('parse')
                   ->with('{"name": "Test"}') // The contents of the test file
                   ->willReturn(['name' => 'Test']);

        $result = $jsonLoader->load('test_valid.json');
        $this->assertEquals(['name' => 'Test'], $result);
    }

    public function testLoadInvalidJsonThrowsException()
    {
        $jsonLoader = $this->getMockBuilder(JsonFileLoader::class)
                           ->onlyMethods(['parse'])
                           ->getMock();

        $this->expectException(\RuntimeException::class);

        // We simulate an invalid JSON case
        $jsonLoader->expects($this->once())
                   ->method('parse')
                   ->with('invalid_json')
                   ->will($this->throwException(new \RuntimeException('Invalid JSON')));

        $jsonLoader->load('test_invalid.json');
    }

    public function testFileNotFoundThrowsException()
    {
        $jsonLoader = new JsonFileLoader();

        $this->expectException(FileNotFoundException::class);

        // Try to load a non-existing file
        $jsonLoader->load('non_existing.json');
    }
}
