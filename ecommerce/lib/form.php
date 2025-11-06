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
	private string $type = "text";
	private bool $isEmail = false;

	function __construct(string $name)
	{
		$this->name = $name;
		$this->field = strtolower($name);
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

		return null;
	}

	function input(array $postData): string
	{
		$value = $this->type === "password" ? "" : $postData[$this->field] ?? "";

		return "<label for=\"{$this->field}\">{$this->name}</label>"
			. "<input type=\"{$this->type}\" id=\"{$this->field}\" name=\"{$this->field}\" value=\"$value\"{$this->props}>";
	}
}

class Form
{
	private array $errors = [];
	private bool $enabled = false;
	private $callback;

	function __construct(string $method, array $postData, array $validationRules, callable $callback)
	{
		if ($method !== "POST")	return;
		$this->enabled = true;
		$this->callback = $callback;

		foreach ($validationRules as $rule) {
			$error = $rule->validate($postData);
			if ($error !== null)
				$this->errors[$rule->field] = $error;
		}

		if (!empty($this->errors)) return;

		$result = ($this->callback)();
		if ($result !== null)
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
