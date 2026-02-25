CREATE TABLE
	IF NOT EXISTS user (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex (randomblob (16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		email TEXT NOT NULL UNIQUE,
		password TEXT NOT NULL
	);

CREATE TABLE
	IF NOT EXISTS session (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex (randomblob (16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		userId VARCHAR(32) NOT NULL,
		FOREIGN KEY (userId) REFERENCES user (id) ON DELETE CASCADE
	);

CREATE TABLE
	IF NOT EXISTS product (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex (randomblob (16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		name TEXT NOT NULL,
		description TEXT,
		price INTEGER NOT NULL -- as pence
	);

-- insert sample products if not exists
INSERT INTO
	product (name, description, price)
SELECT
	'Product 1',
	'A sample product description',
	19999
WHERE
	NOT EXISTS (
		SELECT
			1
		FROM
			product
		WHERE
			name = 'Product 1'
	);

CREATE TABLE
	IF NOT EXISTS purchase (
		id VARCHAR(32) PRIMARY KEY DEFAULT (lower(hex (randomblob (16)))),
		created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		userId VARCHAR(32) NOT NULL,
		productId VARCHAR(32) NOT NULL,
		quantity INTEGER NOT NULL DEFAULT 1 CHECK (quantity > 0),
		completed BOOLEAN NOT NULL DEFAULT 0 CHECK (completed IN (0, 1)),
		FOREIGN KEY (userId) REFERENCES user (id) ON DELETE CASCADE,
		FOREIGN KEY (productId) REFERENCES product (id) ON DELETE CASCADE
	);