<?php

namespace App;

use App\Entity\User;

final class Database
{
	private static $init = <<<SQL
	CREATE TABLE IF NOT EXISTS user (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
		created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		username TEXT NOT NULL UNIQUE,
		email TEXT NOT NULL UNIQUE,
		password TEXT NOT NULL
	);
	SQL;

	// get path from environment variable
	private static function getPath(): string
	{
		$databaseUrl = $_ENV["DATABASE_URL"];
		if ($databaseUrl === false)
			throw new \RuntimeException("DATABASE_URL environment variable is not set.");

		// replace %APP_DIR% with actual app directory
		$databaseUrl = str_replace("%APP_DIR%", dirname(__DIR__), $databaseUrl);

		// Log::info("Database path after replacement: " . $databaseUrl);

		return $databaseUrl;
	}

	private static ?\PDO $pdo = null;

	final public static function pdo(): \PDO
	{
		if (self::$pdo)
			return self::$pdo;

		$databasePath = self::getPath();

		// check if database file exists, if not create it

		// check if string starts with sqlite:
		if (str_starts_with($databasePath, "sqlite:")) {
			$path = substr($databasePath, 7);
			$dir = dirname($path);
			if (!is_dir($dir))
				mkdir($dir, 0777, true);

			if (!file_exists($path))
				// create empty file
				touch($path);
		}

		self::$pdo = new \PDO(
			$databasePath,
			$_ENV["DATABASE_USER"] ?? null,
			$_ENV["DATABASE_PASSWORD"] ?? null,
			[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
		);
		self::$pdo->exec(self::$init);

		return self::$pdo;
	}

	final public static function getRandomNumber(): int
	{
		$stmt = self::pdo()->query("SELECT RANDOM() % 100 AS number");
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		return (int) $row["number"];
	}

	final public static function getUserByUsername(string $username): ?User
	{
		$stmt = self::pdo()->prepare("SELECT id, created, password FROM user WHERE username = :username");
		$stmt->execute(["username" => $username]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$row)
			return null;

		return new User(
			$row["id"],
			new \DateTime($row["created"]),
			$username,
			$row["password"]
		);
	}

	final public static function checkUser(string $username, string $passwordRaw): ?User
	{
		$stmt = self::pdo()->prepare("SELECT id, created, password FROM user WHERE username = :username");
		$stmt->execute(["username" => $username]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$row)
			return null;

		if (!password_verify($passwordRaw, $row["password"]))
			return null;

		return new User(
			$row["id"],
			new \DateTime($row["created"]),
			$username,
			$row["password"],
		);
	}

	final public static function registerUser(string $username, string $email, string $password): ?User
	{
		try {
			// Use RETURNING to get the inserted row in a single query (works on SQLite 3.35+)
			$stmt = self::pdo()->prepare(
				"INSERT INTO user (username, email, password) VALUES (:username, :email, :password) RETURNING id, created, password;"
			);
			$stmt->execute([
				"username" => $username,
				"email" => $email,
				"password" => password_hash($password, PASSWORD_ARGON2ID),
			]);
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!$row)
				return null;

			return new User(
				$row["id"],
				new \DateTime($row["created"]),
				$username,
				$row["password"]
			);
		} catch (\PDOException $e) {
			Log::error("Database error during registration: " . $e->getMessage());
			return null;
		}
	}
}
