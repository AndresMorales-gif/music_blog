<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactoControllerTest extends WebTestCase
{

    public function testShowContacto()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contacto');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertSelectorTextContains('html #formContact h1', 'Contacto');
    }

    public function testContactoForm()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/contacto');

        $form = $crawler->selectButton('enviar')->form();

		// set some values
		$form['messages[name]'] = 'Test';
		$form['messages[email]'] = 'test@gmail.com';
		$form['messages[message]'] = 'Mensaje de test';

		// submit the form
		$crawler = $client->submit($form);

		$this->assertResponseRedirects('/');
    }

}
