<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testShowHome()
    {
        $client = static::createClient();
        $client->request('GET', '/');        

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertSelectorTextContains('html #title h1', 'Hablemos de música!!!');
    }

    public function testMenu() 
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/');

    	$link = $crawler
		    ->filter('html #menu a:contains("Blog")')
		    ->link();

		$client->click($link);    
		$this->assertEquals(200, $client->getResponse()->getStatusCode());    
    }

}

