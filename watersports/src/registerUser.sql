-- Use RETURNING to get the inserted row in a single query (works on SQLite 3.35+)
INSERT INTO
	user (email, password)
VALUES
	(?, ?) RETURNING id,
	created,
	password;