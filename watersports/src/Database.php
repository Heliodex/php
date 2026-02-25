<?php

namespace App;

use App\Entity\{Product, User};

final class Database
{
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

		$initQuery = file_get_contents(__DIR__ . "/init.sql");
		self::$pdo->exec($initQuery);

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
		$getUserBySessionQuery = file_get_contents(__DIR__ . "/getUserBySession.sql");
		$stmt = self::pdo()->prepare($getUserBySessionQuery);
		$stmt->execute([$sess]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		if (!$row)
			return null;

		return new User(
			$row["id"],
			new \DateTime($row["created"]),
			$row["email"],
			$row["password"],
			$row["cartSize"],
		);
	}

	final public static function createSession(string $userId): string
	{
		$createSessionQuery = file_get_contents(__DIR__ . "/createSession.sql");
		$stmt = self::pdo()->prepare($createSessionQuery);
		$stmt->execute([$userId]);
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $row["id"];
	}

	final public static function invalidateSession(string $sessionId): void
	{
		$stmt = self::pdo()->prepare("DELETE FROM session WHERE id = ?");
		$stmt->execute([$sessionId]);
	}

	final public static function checkUser(string $email, string $passwordRaw): ?User
	{
		$getUserByEmailQuery = file_get_contents(__DIR__ . "/getUserByEmail.sql");
		$stmt = self::pdo()->prepare($getUserByEmailQuery);
		$stmt->execute([$email]);
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
			$getUserByEmailQuery = file_get_contents(__DIR__ . "/getUserByEmail.sql");
			$stmt = self::pdo()->prepare($getUserByEmailQuery);
			$stmt->execute([$email]);

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
			$registerUserQuery = file_get_contents(__DIR__ . "/registerUser.sql");
			$stmt = self::pdo()->prepare(
				$registerUserQuery
			);
			$stmt->execute([
				$email,
				password_hash($passwordRaw, PASSWORD_ARGON2ID),
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

	final public static function updatePassword(string $userId, string $newPasswordRaw): bool
	{
		try {
			$stmt = self::pdo()->prepare("UPDATE user SET password = :password WHERE id = :userId");
			$stmt->execute([
				"userId" => $userId,
				"password" => password_hash($newPasswordRaw, PASSWORD_ARGON2ID),
			]);
			return true;
		} catch (\PDOException $e) {
			Log::error("Database error during password update: {$e->getMessage()}");
			return false;
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
					0,
					$row["inCart"],
				);
			}
			return $products;
		} catch (\PDOException $e) {
			Log::error("Database error during product retrieval: {$e->getMessage()}");
			return [];
		}
	}

	final public static function getCart(string $userId): array
	{
		try {
			$stmt = self::pdo()->prepare(
				"SELECT p.id, p.created, p.name, p.description, p.price, pu.quantity
				FROM product p
				INNER JOIN purchase pu ON p.id = pu.productId
				WHERE pu.userId = :userid AND pu.completed = false"
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
					$row["quantity"],
					true,
				);
			}
			return $products;
		} catch (\PDOException $e) {
			Log::error("Database error during cart retrieval: {$e->getMessage()}");
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

	final public static function setCartQuantity(string $userId, string $productId, int $qty): void
	{
		// sending quantity directly removes 1 database query compared to querying for the quantity

		try {
			if ($qty < 1) {
				self::changeCart($userId, $productId, false);
				return;
			}

			$stmt = self::pdo()->prepare("UPDATE purchase SET quantity = :qty WHERE userId = :userId AND productId = :productId AND completed = 0");
			$stmt->execute([
				"qty" => $qty,
				"userId" => $userId,
				"productId" => $productId,
			]);
		} catch (\PDOException $e) {
			Log::error("Database error during cart quantity change: {$e->getMessage()}");
		}
	}
}
