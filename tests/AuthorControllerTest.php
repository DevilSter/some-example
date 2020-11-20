<?php
declare(strict_types=1);

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Список процедур дефолтный
     */
    public function testAuthorList() {
        $this->client->request('GET', '/author');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertIsArray($data);
        $this->assertCount(20, $data);
    }

    /**
     * Список процедур с изменением лимита
     */
    public function testAuthorListChangeLimits() {
        $this->client->request('GET', '/author/1/40');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(40, $data);
    }

    /**
     * Создание автора
     *
     */
    public function testAuthorCreate() {
        $this->client->request(
            'POST',
            '/author/create',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{
                        "first_name":"FirstName 12",
                        "middle_name": "",
                        "last_name":"LastName"
                    }');

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Проверяем что дубль нельзя положить
     */
    public function testAuthorCreateUniqueError() {
        $this->client->request(
            'POST',
            '/author/create',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{
                        "first_name":"FirstName 12",
                        "middle_name": "",
                        "last_name":"LastName"
                    }');
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'POST',
            '/author/create',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{
                        "first_name":"FirstName 12",
                        "middle_name": "",
                        "last_name":"LastName"
                    }');


        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}