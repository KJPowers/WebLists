/*
   Bootstrap the schema and user(s).  You can omit weblists_admin if your ops do not require it.

   1) Create the users
      weblists_admin: Optional if your ops require it. Can run DDL against schema. For updates only.
      weblists_user: Can not run DDL against schema. App server will never run DDL.
 */
CREATE USER weblists_admin WITH PASSWORD '<changeme>';

CREATE USER weblists_user WITH PASSWORD '<changeme_too>';

/* 2) Create the database/schema */
CREATE SCHEMA weblists;

/* 3) Bootstrap privileges */
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA weblists TO weblists_admin;
GRANT USAGE ON SCHEMA weblists TO weblists_user;

/*
-- revert
DROP SCHEMA weblists;

DROP USER IF EXISTS weblists_user, weblists_admin;
*/
