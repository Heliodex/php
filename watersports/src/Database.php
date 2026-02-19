<?php

namespace App;

use App\Entity\{Product, User};

final class Database
{
	private static string $init = <<<SQL
	CREATE TABLE IF NOT EXISTS user (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		email TEXT NOT NULL UNIQUE,
		password TEXT NOT NULL
	);
	CREATE TABLE IF NOT EXISTS session (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		userId VARCHAR(32) NOT NULL,
		FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE
	);
	CREATE TABLE IF NOT EXISTS product (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		name TEXT NOT NULL,
		description TEXT,
		price INTEGER NOT NULL -- as pence
	);

	-- insert sample products if not exists
	INSERT INTO product (name, description, price)
	SELECT 'Product 1', 'A sample product description', 19999
	WHERE NOT EXISTS (SELECT 1 FROM product WHERE name = 'Product 1');

	CREATE TABLE IF NOT EXISTS purchase (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		userId VARCHAR(32) NOT NULL,
		productId VARCHAR(32) NOT NULL,
		completed BOOLEAN NOT NULL DEFAULT 0 CHECK (completed IN (0, 1)),
		FOREIGN KEY (userId) REFERENCES user(id) ON DELETE CASCADE,
		FOREIGN KEY (productId) REFERENCES product(id) ON DELETE CASCADE
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

	final public static function getUserBySessionId(string $sess): ?User
	{
		$stmt = self::pdo()->prepare("SELECT u.id, u.created, u.password, u.email FROM user u INNER JOIN session s ON u.id = s.userId WHERE s.id = :sess");
		$stmt->execute(["sess" => $sess]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$row)
			return null;

		return new User(
			$row["id"],
			new \DateTime($row["created"]),
			$row["email"],
			$row["password"]
		);
	}

	final public static function createSession(string $userId): string
	{
		$stmt = self::pdo()->prepare("INSERT INTO session (userId) VALUES (:userId) RETURNING id");
		$stmt->execute(["userId" => $userId]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $row["id"];
	}

	final public static function invalidateSession(string $sessionId): void
	{
		$stmt = self::pdo()->prepare("DELETE FROM session WHERE id = :sessionId");
		$stmt->execute(["sessionId" => $sessionId]);
	}

	final public static function checkUser(string $email, string $passwordRaw): ?User
	{
		$stmt = self::pdo()->prepare("SELECT id, created, password FROM user WHERE email = :email");
		$stmt->execute(["email" => $email]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$row)
			return null;

		if (!password_verify($passwordRaw, $row["password"]))
			return null;

		return new User(
			$row["id"],
			new \DateTime($row["created"]),
			$email,
			$row["password"],
		);
	}

	final public static function logInUser(string $email, string $passwordRaw): ?string
	{
		try {
			$stmt = self::pdo()->prepare("SELECT id, created, password FROM user WHERE email = :email");
			$stmt->execute(["email" => $email]);

			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!$row)
				return null;

			if (!password_verify($passwordRaw, $row["password"]))
				return null;

			return self::createSession($row["id"]);
		} catch (\PDOException $e) {
			Log::error("Database error during login: {$e->getMessage()}");
			return null;
		}
	}

	final public static function registerUser(string $email, string $passwordRaw): ?string
	{
		try {
			// Use RETURNING to get the inserted row in a single query (works on SQLite 3.35+)
			$stmt = self::pdo()->prepare(
				"INSERT INTO user (email, password) VALUES (:email, :password) RETURNING id, created, password;"
			);
			$stmt->execute([
				"email" => $email,
				"password" => password_hash($passwordRaw, PASSWORD_ARGON2ID),
			]);
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!$row)
				return null;

			return self::createSession($row["id"]);
		} catch (\PDOException $e) {
			Log::error("Database error during registration: {$e->getMessage()}");
			return null;
		}
	}

	final public static function getProducts(string $userId): array
	{
		try {
			$stmt = self::pdo()->prepare(
				"SELECT
					p.id, p.created, p.name, p.description, p.price,
					EXISTS (SELECT 1 FROM purchase WHERE userId = :userid AND productId = p.id AND completed = false) AS inCart
				FROM product p"
			);
			$stmt->execute(["userid" => $userId]);
			$products = [];
			while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
				$products[] = new Product(
					$row["id"],
					new \DateTime($row["created"]),
					$row["name"],
					$row["description"],
					$row["price"],
					$row["inCart"],
				);
			}
			return $products;
		} catch (\PDOException $e) {
			Log::error("Database error during product retrieval: {$e->getMessage()}");
			return [];
		}
	}

	final public static function changeCart(string $userId, string $productId, bool $add): void
	{
		try {
			$stmt = self::pdo()->prepare(
				$add ? "INSERT INTO purchase (userId, productId) VALUES (:userId, :productId)"
				: "DELETE FROM purchase WHERE userId = :userId AND productId = :productId AND completed = 0"
			);
			$stmt->execute([
				"userId" => $userId,
				"productId" => $productId,
			]);
		} catch (\PDOException $e) {
			Log::error("Database error during cart change: {$e->getMessage()}");
		}
	}
}
