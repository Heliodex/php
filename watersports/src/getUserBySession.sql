SELECT
	u.id,
	u.created,
	u.password,
	u.email,
	(
		SELECT
			COUNT(*)
		FROM
			purchase pu
		WHERE
			pu.userId = u.id
			AND pu.completed = 0
	) AS cartSize
FROM
	user u
	INNER JOIN session s ON u.id = s.userId
WHERE
	s.id = ?;