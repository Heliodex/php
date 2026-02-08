<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

final class Log
{
	private static ?Logger $logger = null;

	final public static function logger(): Logger
	{
		if (self::$logger)
			return self::$logger;

		self::$logger = new Logger("app");
		self::$logger->pushHandler(new StreamHandler("php://stdout"));

		return self::$logger;
	}

	final public static function info(string $message): void
	{
		self::logger()->info($message);
	}

	final public static function error(string $message): void
	{
		self::logger()->error($message);
	}
}
