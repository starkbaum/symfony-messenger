<?php


namespace App\Tests\Controller;



use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagePostControllerTest extends WebTestCase
{
    /** @test */
    public function testCreate()
    {
        $client = static::createClient();

        $uploadedFile = new UploadedFile(
            __DIR__ . '/../fixtures/stock_001.jpg',
            'stock_001.jpg'
        );

        $client->request('POST', '/api/images', [], [
            'file' => $uploadedFile,
        ]);

        $this->assertResponseIsSuccessful();
    }
}