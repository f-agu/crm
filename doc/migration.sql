/*
On "source" database :

mysqldump -h127.0.0.1 -p<password> <database> develeve_site develeve_blacklist develeve_cenacle devclub > dump_src.sql

On "destination" database :

mysql -h127.0.0.1 -p<password> --database=<database> < dump_src.sql



*/



DROP FUNCTION IF EXISTS `camel_case`;

DELIMITER $$

CREATE FUNCTION `camel_case`(str varchar(128)) RETURNS varchar(128)
BEGIN
DECLARE n, pos INT DEFAULT 1;
DECLARE sub, proper VARCHAR(128) DEFAULT '';

if length(trim(str)) > 0 then
    WHILE pos > 0 DO
        set pos = locate(' ',trim(str),n);
        if pos = 0 then
            set sub = lower(trim(substr(trim(str),n)));
        else
            set sub = lower(trim(substr(trim(str),n,pos-n)));
        end if;

        set proper = concat_ws(' ', proper, concat(upper(left(sub,1)),substr(sub,2)));
        set n = pos + 1;
    END WHILE;
end if;

RETURN trim(proper);
END 
$$

DELIMITER;


-- ============================================

-- **** CLUB ****

CREATE TABLE zzmigr_club AS 
 SELECT id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        camel_case(nom) AS name,
        coalesce(logo, 'default.png') AS logo,
        url AS website_url,
        url_fb AS facebook_url,
        mailing_list,
        a_supprimer = 'N' AS active
   FROM devclub;


INSERT INTO club(uuid, name, logo, website_url, facebook_url, mailing_list, active)
 SELECT uuid, name, logo, website_url, facebook_url, mailing_list, active
  FROM zzmigr_club;

-- **** CLUB_LOCATION ****

CREATE TABLE zzmigr_club_location AS 
 SELECT oc.id AS o_id,
        nc.id AS n_id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        '?' AS name,
        '?' AS address,
        camel_case(ville) AS city,
        '?' AS zipcode,
        departement AS county,
        camel_case(pays) AS country
  FROM devclub oc
   JOIN zzmigr_club mc USING (id)
   JOIN club nc USING (uuid);

INSERT INTO club_location(uuid, name, address, city, zipcode, county, country)
 SELECT uuid, name, address, city, zipcode, county, country
  FROM zzmigr_club_location;


-- **** CLUB_LESSON ****

CREATE TABLE zzmigr_club_lesson AS 
 SELECT mcl.o_id AS o_id,
        ncl.id AS club_location_id,
        nc.id AS club_id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        1 AS point,
        'Taekwondo' AS discipline,
        'Tous niveaux' AS age_level,
        'monday' AS day_of_week,
        '19:00:00' AS start_time,
        '20:00:00' AS end_time      
  FROM zzmigr_club mc
   JOIN zzmigr_club_location mcl ON mc.id = mcl.o_id
   JOIN club nc ON mc.uuid = nc.uuid
   JOIN club_location ncl ON mcl.uuid = ncl.uuid;

INSERT INTO club_lesson(club_location_id, club_id, uuid, point, discipline, age_level, day_of_week, start_time, end_time)
 SELECT club_location_id, club_id, uuid, point, discipline, age_level, day_of_week, start_time, end_time
  FROM zzmigr_club_lesson;



-- **** USER ****

CREATE TABLE zzmigr_user AS 
 SELECT eleve_id AS o_id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        Nom AS lastname,
        Prenom AS firstname,
        Sexe AS sex,
        Date_naissance AS birthday,
        Adresse AS address,
        Code_postal AS zipcode,
        Ville AS city,
        Tel AS phone,
        Tel_accident AS phone_emergency,
        Nationalite AS nationality,
        Email AS mails,
        Date_ins AS created,
        0 AS blacklist_id,
        null AS blacklist_date,
        null AS blacklist_reason
  FROM develeve_cenacle;

INSERT INTO zzmigr_user
 SELECT 0 AS o_id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        Nom AS lastname,
        Prenom AS firstname,
        Sexe AS sex,
        Date_naissance AS birthday,
        Adresse AS address,
        Code_postal AS zipcode,
        Ville AS city,
        Tel AS phone,
        Tel_accident AS phone_emergency,
        Nationalite AS nationality,
        Email AS mails,
        str_to_date('2000-01-01 0:00:00','%Y-%m-%d %H:%i:%s') AS created,
        Id AS blacklist_id,
        Date_blacklist AS blacklist_date,
        Motif AS blacklist_reason
  FROM develeve_blacklist;


INSERT INTO user(uuid, lastname, firstname, sex, birthday, address, zipcode, city, phone, phone_emergency, nationality, mails, created, blacklist_date, blacklist_reason)
 SELECT uuid, lastname, firstname, sex, birthday, address, zipcode, city, phone, phone_emergency, nationality, mails, created, blacklist_date, blacklist_reason
  FROM zzmigr_user;


-- **** ACCOUNT ****

CREATE TABLE zzmigr_account AS 
 SELECT eleve_id AS o_id,
        u.id AS user_id,
        o_s.Email AS login,
        concat('sha1:', substr(Pwd, 1, 40)) AS password,
        json_array(
           if(Resp_Club IS NOT NULL AND Resp_Club <> '', 'ROLE_CLUB_MANAGER', 'ROLE_USER'),
           if(Statut = 'Prof', 'ROLE_TEACHER', 'ROLE_USER'),
           if(remove_accents(Statut) = 'Eleve', 'ROLE_STUDENT', 'ROLE_USER')
        ) AS roles,
        Acces = 'O' AS has_access
  FROM develeve_site o_s
   JOIN zzmigr_user z_u ON o_s.eleve_id = z_u.o_id
   JOIN user u ON u.uuid = z_u.uuid;

UPDATE zzmigr_account
 SET roles = replace(replace(replace(roles, ', "ROLE_USER"', ''), '["ROLE_USER", ', '['), ', "ROLE_USER"]', ']')

INSERT INTO account(user_id, login, password, roles, has_access)
 SELECT user_id, login, password, roles, has_access
  FROM zzmigr_account;


-- **** USER_LESSON_SUBSCRIBE ****

SELECT *
   
 FROM develeve_cenacle
  JOIN 

-- **** << DROP temporary tables >> ****

DROP TABLE zzmigr_club;
DROP TABLE zzmigr_club_location;
DROP TABLE zzmigr_club_lesson;
DROP TABLE zzmigr_user;
DROP TABLE zzmigr_account;










  