-- #################### SEARCH ####################

-- USER
SELECT 'user', id, CONCAT(lastname, ' ', firstname)
 FROM user
 WHERE remove_accents(lower(lastname)) LIKE :query 
    OR remove_accents(lower(firstname)) LIKE :query
    OR remove_accents(lower(mails)) LIKE :query
    OR remove_accents(lower(address)) LIKE :query
    OR remove_accents(lower(city)) LIKE :query
    OR remove_accents(lower(nationality)) LIKE :query

-- ACCOUNT
SELECT 'account', a.id, CONCAT(lastname, ' ', firstname)
 FROM account a
  JOIN user u ON a.user_id = u.id
 WHERE remove_accents(lower(login)) LIKE :query
 GROUP BY 1, 2, 3

-- CLUB
SELECT 'club', c.id, c.name
 FROM club c
  JOIN club_lesson cles ON (cles.club_id = c.id AND c.active)
  JOIN club_location cl ON cles.club_location_id = cl.id
 WHERE remove_accents(lower(c.name)) LIKE :query
    OR remove_accents(lower(cl.name)) LIKE :query
    OR remove_accents(lower(cl.city)) LIKE :query
    OR remove_accents(lower(cl.address)) LIKE :query
    OR remove_accents(lower(cl.zipcode)) LIKE :query
    OR remove_accents(lower(cl.county)) LIKE :query
    OR remove_accents(lower(cl.country)) LIKE :query
    OR remove_accents(lower(cles.discipline)) LIKE :query
    OR remove_accents(lower(cles.age_level)) LIKE :query
 GROUP BY 1, 2, 3
    