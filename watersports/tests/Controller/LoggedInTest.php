<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoggedInTest extends WebTestCase
{
	final private function logIn(KernelBrowser $client)
	{
		$client->request("GET", "/login");
		$this->assertResponseIsSuccessful();

		// complete registration form
		$client->submitForm("Log in", [
			"username" => "testuser",
			// "email" => "testuser@example.com",
			"password" => "testpassword",
			// "confirmPassword" => "testpassword",
		]);
		$this->assertResponseRedirects("/home");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}


	final public function testIndex(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/");
		$this->assertResponseRedirects("/home");
	}

	final public function testLogin(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/login");
		$this->assertResponseRedirects("/home");
	}

	final public function testRegister(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/register");
		$this->assertResponseRedirects("/home");
	}

	final public function testHome(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/home");
		$this->assertResponseIsSuccessful();
	}

	final public function testLogout(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("POST", "/logout");
		$this->assertResponseRedirects("/login");
	}
}
