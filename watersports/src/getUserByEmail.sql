SELECT
	id,
	created,
	password
FROM
	user
WHERE
	email = ?;