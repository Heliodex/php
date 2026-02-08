<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoggedOutTest extends WebTestCase
{
	final public function testIndex(): void
	{
		$client = self::createClient();
		$client->request("GET", "/");
		$this->assertResponseIsSuccessful();
	}

	final public function testLogin(): void
	{
		$client = self::createClient();
		$client->request("GET", "/login");
		$this->assertResponseIsSuccessful();
	}

	final public function testRegister(): void
	{
		$client = self::createClient();
		$client->request("GET", "/register");
		$this->assertResponseIsSuccessful();
	}

	final public function testHome(): void
	{
		$client = self::createClient();
		$client->request("GET", "/home");
		$this->assertResponseRedirects("/login");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}

	final public function testLogout(): void
	{
		$client = self::createClient();
		$client->request("POST", "/logout");
		$this->assertResponseRedirects("/login");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}
}
