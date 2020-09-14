<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

    public function testShowRegister()
    {
        $client = static::createClient();
        $client->request('GET', '/registro');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html #content h1', 'Unete y escribe con nosotros!!');
    }

    public function testRegistrationForm()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/registro');

        $form = $crawler->selectButton('register')->form();

		// set some values
		$form['registration_form[username]'] = 'UserTest';
		$form['registration_form[name]'] = 'User';
		$form['registration_form[last_name]'] = 'Test';
		$form['registration_form[email]'] = 'User@gmail.com';
		$form['registration_form[agreeTerms]'] = true;
		$form['registration_form[plainPassword]'] = '123456789';

		// submit the form
		$crawler = $client->submit($form);

		$this->assertResponseRedirects('/');
    }

    public function testRequestAjax()
    {
    	$client = static::createClient();
    	$client->xmlHttpRequest('POST', '/registro/disponible', ['column' => 'username', 'data' => 'UserTest']);
    	$content = $client->getResponse()->getContent();
    	$responseJson = json_decode($content);
    	
    	$this->assertEquals(200, $client->getResponse()->getStatusCode());
    	$this->assertEquals(false, $responseJson->data);
    }

}
