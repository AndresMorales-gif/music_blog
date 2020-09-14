<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testShowBlog()
    {
        $client = static::createClient();
        $client->request('GET', '/blog');        

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertSelectorTextContains('html #titBlog h1', 'Nuestros Blogs');
    }

    public function testPosts()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/blog');
    	
    	$link = $crawler
    		->filter('html #listBlogs a')
    		->eq(0)
    		->link();		
		
		$client->click($link);    
		$this->assertEquals(200, $client->getResponse()->getStatusCode()); 	    
    }

    
}
