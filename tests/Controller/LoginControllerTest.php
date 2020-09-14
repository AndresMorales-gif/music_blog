<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{

    public function testShowLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertSelectorTextContains('html form h1', 'Te estamos esperando!!');
    }

    public function testLoginForm()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('iniciar')->form();

		// set some values
		$form['username'] = 'AndresMorales';
		$form['password'] = '123456789';
		
		// submit the form
		$crawler = $client->submit($form);

		$this->assertResponseRedirects('/');
    }

}
