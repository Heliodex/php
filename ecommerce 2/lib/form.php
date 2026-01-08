<?php

declare(strict_types=1);

class Rule // 34
{
	private string $name;
	public string $field;

	private string $props = "";
	private bool $required = false;
	private int $minlen = 0;
	private ?int $maxlen = null;
	private ?int $minval = 0;
	private ?int $maxval = null;
	private string $type = "text";
	private bool $isEmail = false;
	private bool $isDecimal = false;

	function __construct(string $name)
	{
		$this->name = $name;
		$this->field = str_replace(" ", "_", strtolower($name));
	}

	function required(): self
	{
		$this->required = true;
		$this->props .= " required";
		return $this;
	}

	function minLength(int $minLength): self
	{
		$this->minlen = $minLength;
		$this->props .= " minLength=\"$minLength\"";
		return $this;
	}

	function maxLength(int $maxLength): self
	{
		$this->maxlen = $maxLength;
		$this->props .= " maxLength=\"$maxLength\"";
		return $this;
	}

	function email(): self
	{
		$this->isEmail = true;
		return $this;
	}

	function password(): self
	{
		$this->type = "password";
		return $this;
	}

	function number(): self
	{
		$this->type = "number";
		return $this;
	}

	function time(): self
	{
		$this->type = "time";
		return $this;
	}

	function file(): self
	{
		$this->type = "file";
		return $this;
	}

	function textarea(): self
	{
		$this->type = "textarea";
		return $this;
	}

	function minValue(int $minValue): self
	{
		if ($this->type !== "number")
			throw new Exception("minValue can only be set for number fields");

		$this->minval = $minValue;
		$this->props .= " min=\"$minValue\"";
		return $this;
	}

	function maxValue(int $maxValue): self
	{
		if ($this->type !== "number")
			throw new Exception("maxValue can only be set for number fields");

		$this->maxval = $maxValue;
		$this->props .= " max=\"$maxValue\"";
		return $this;
	}

	function maxSize(int $maxSize): self
	{
		if ($this->type !== "file")
			throw new Exception("maxSize can only be set for file fields");

		$this->props .= " max-size=\"$maxSize\"";
		return $this;
	}

	function mediaTypes(array $types): self
	{
		if ($this->type !== "file")
			throw new Exception("mediaTypes can only be set for file fields");

		$this->props .= " accept=\"" . implode(",", $types) . "\"";
		return $this;
	}

	function decimal(): self
	{
		$this->isDecimal = true;
		if ($this->type !== "number")
			throw new Exception("decimal can only be set for number fields");

		$this->props .= " step=\"0.01\"";
		return $this;
	}

	function validate(array $data): ?string
	{
		$value = $data[$this->field] ?? "";

		if ($this->required && empty($value))
			return "{$this->name} is required";
		if (strlen($value) < $this->minlen)
			return "{$this->name} must be at least {$this->minlen} characters long";
		if ($this->maxlen !== null && strlen($value) > $this->maxlen)
			return "{$this->name} must be at most {$this->maxlen} characters long";
		if ($this->isEmail && !filter_var($value, FILTER_VALIDATE_EMAIL))
			return "{$this->name} must be a valid email address";

		if ($this->type === "number") {
			if (!is_numeric($value))
				return "{$this->name} must be a valid number";
			if ($this->minval !== null && $value < $this->minval)
				return "{$this->name} must be at least {$this->minval}";
			if ($this->maxval !== null && $value > $this->maxval)
				return "{$this->name} must be at most {$this->maxval}";

			$float = floatval($value);
			if ($this->isDecimal && $float != round($float, 2))
				return "{$this->name} must be a valid decimal number with at most two decimal places";
		} elseif ($this->type === "file") {
			if ($this->required && (!isset($data[$this->field]) || $data[$this->field]["error"] === UPLOAD_ERR_NO_FILE))
				return "{$this->name} is required";
		}

		return null;
	}

	function input(array $postData): string
	{
		$value =
			($this->type === "password" or $this->type === "file")
			? "" : ($postData[$this->field] ?? "");

		$v = "<label for=\"{$this->field}\">{$this->name}</label>";

		if ($this->type === "textarea")
			return "$v<textarea id=\"{$this->field}\" name=\"{$this->field}\"$this->props>$value</textarea>";

		$v .= "<input type=\"{$this->type}\" id=\"{$this->field}\" name=\"{$this->field}\"";

		if ($value !== "" && $this->type !== "file")
			$v .= "value=\"$value\"";

		return  "$v$this->props>";
	}
}

class Form
{
	private array $errors = [];
	private bool $enabled = false;
	private $callback;

	function __construct(string $formName, string $method, array $getData, array $postData, array $validationRules, callable $callback)
	{
		if ($method !== "POST" or !isset($getData["/$formName"]))
			return;
		$this->enabled = true;
		$this->callback = $callback;

		foreach ($validationRules as $rule) {
			$error = $rule->validate($postData);
			if ($error !== null)
				$this->errors[$rule->field] = $error;
		}

		if (!empty($this->errors)) return;

		$result = ($this->callback)();
		if ($result === null)
			die;

		$this->errors = array_merge($this->errors, $result);
	}

	function isValid(): bool
	{
		return $this->enabled && empty($this->errors);
	}

	function errorNotification(string $field): ?string
	{
		$fieldError = $this->errors[$field] ?? null;
		if (is_null($fieldError))
			return null;
		return "<small class=\"formerror\">{$fieldError}</small>";
	}

	function overrideError(string $field, string $message): void
	{
		$this->errors[$field] = $message;
	}
}
