<?php

namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
	private static ?Logger $logger = null;

	final public static function logger(): Logger
	{
		if (self::$logger === null) {
			self::$logger = new Logger("app");
			self::$logger->pushHandler(new StreamHandler("php://stdout"));
			// self::info("Logger initialised.");
		}
		return self::$logger;
	}

	final public static function info(string $message): void
	{
		self::logger()->info($message);
	}
}
