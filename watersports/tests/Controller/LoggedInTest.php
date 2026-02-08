<?php

namespace App\Tests\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoggedInTest extends WebTestCase
{
	private function logIn(KernelBrowser $client): void
	{
		// Create test user if it doesn't exist
		$testUser = Database::checkUser("testuser", "testpassword");
		if (!$testUser) {
			Database::registerUser("testuser", "testuser@example.com", "testpassword");
			// Re-fetch to ensure we have a fresh instance
			$testUser = Database::checkUser("testuser", "testpassword");
		}

		// First, get the login form to extract the CSRF token
		$client->request("GET", "/login");
		$crawler = $client->getCrawler();

		// Extract CSRF token from the form
		$csrfToken = $crawler->selectButton("Log in")->form()->get("login[_token]")->getValue();

		// Submit login form with CSRF token
		$client->request("POST", "/login", [
			"login" => [
				"username" => "testuser",
				"password" => "testpassword",
				"_token" => $csrfToken,
				"submit" => "Log in",
			],
		]);

		// Follow redirects to complete the login process
		if ($client->getResponse()->isRedirect()) {
			$client->followRedirect();
		}
	}

	public function testIndex(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/");
		// Authenticated users cannot access index page, should redirect to /home
		$this->assertResponseRedirects("/home");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}

	public function testLogin(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/login");
		// Authenticated users cannot access login page
		$this->assertResponseRedirects("/home");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}

	public function testRegister(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		$client->request("GET", "/register");
		// Authenticated users cannot access register page
		$this->assertResponseRedirects("/home");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}

	public function testHome(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		// After logIn(), make a request to /home
		$client->request("GET", "/home");
		$this->assertResponseIsSuccessful();
	}

	public function testLogout(): void
	{
		$client = self::createClient();
		$this->logIn($client);
		// After logIn(), make a POST request to /logout
		$client->request("POST", "/logout");
		// User is logged out, should redirect to /login
		$this->assertResponseRedirects("/login");
		$client->followRedirect();
		$this->assertResponseIsSuccessful();
	}
}
