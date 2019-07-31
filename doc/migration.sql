
DROP FUNCTION IF EXISTS fagu1.`proper_case`;

DELIMITER $$

CREATE FUNCTION fagu1.`proper_case`(str varchar(128)) RETURNS varchar(128)
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

DELIMITER ;


-- ============================================

-- **** CLUB ****

CREATE TABLE fagu1.zzmigr_club AS 
 SELECT id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        fagu1.proper_case(nom) AS name,
        coalesce(logo, 'default.png') AS logo,
        url AS website_url,
        url_fb AS facebook_url,
        mailing_list,
        a_supprimer = 'N' AS active
   FROM cenacle_webdev.devclub;


INSERT INTO fagu1.club(uuid, name, logo, website_url, facebook_url, mailing_list, active)
 SELECT uuid, name, logo, website_url, facebook_url, mailing_list, active
  FROM fagu1.zzmigr_club;

-- **** CLUB_LOCATION ****

CREATE TABLE fagu1.zzmigr_club_location AS 
 SELECT oc.id AS o_id,
        nc.id AS n_id,
        concat(lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)), lower(lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0))) AS uuid,
        '?' AS name,
        '?' AS address,
        fagu1.proper_case(ville) AS city,
        '?' AS zipcode,
        departement AS county,
        fagu1.proper_case(pays) AS country
  FROM cenacle_webdev.devclub oc
   JOIN fagu1.zzmigr_club mc USING (id)
   JOIN fagu1.club nc USING (uuid);

INSERT INTO fagu1.club_location(uuid, name, address, city, zipcode, county, country)
 SELECT uuid, name, address, city, zipcode, county, country
  FROM fagu1.zzmigr_club_location;


-- **** CLUB_LESSON ****

CREATE TABLE fagu1.zzmigr_club_lesson AS 
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
  FROM fagu1.zzmigr_club mc
   JOIN fagu1.zzmigr_club_location mcl ON mc.id = mcl.o_id
   JOIN club nc ON mc.uuid = nc.uuid
   JOIN club_location ncl ON mcl.uuid = ncl.uuid;

INSERT INTO fagu1.club_lesson(club_location_id, club_id, uuid, point, discipline, age_level, day_of_week, start_time, end_time)
 SELECT club_location_id, club_id, uuid, point, discipline, age_level, day_of_week, start_time, end_time
  FROM fagu1.zzmigr_club_lesson;


-- **** << DROP temporary tables >> ****

DROP TABLE fagu1.zzmigr_club;
DROP TABLE fagu1.zzmigr_club_location;
DROP TABLE zzmigr_club_lesson;
