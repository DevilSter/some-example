<?php
declare(strict_types=1);

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
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
     * Создание книги с одним автором
     */
    public function testBookCreate() {

        $this->client->request(
            'POST',
            '/book/create',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{
                        "title":"FirstName 12",
                        "authors": ['.$this->getSomeAuthor().']
                    }');

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Ошибка создания книги без авторов
     */
    public function testBookCreateError() {

        $this->client->request(
            'POST',
            '/book/create',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{
                        "title":"FirstName 12",
                        "authors": []
                    }');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Поиск по названию книги
     */
    public function testBookSearch() {
        $this->client->request(
            'POST',
            '/book/search',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            '{"title":"Алмазы Того"}');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals("Алмазы Того", $data['title']);
    }

    private function getSomeAuthor(): int {
        $this->client->request('GET', '/author');
        $data = json_decode($this->client->getResponse()->getContent(), true);

        return $data[0]['id'];
    }
}