/* 1) Create the users */
CREATE USER 'weblists_admin'@'localhost' IDENTIFIED BY '';

CREATE USER 'weblists_user'@'localhost'  IDENTIFIED BY '';

/* 2) Create the database/schema */
CREATE DATABASE weblists;

/* 3) Grant privileges */
GRANT ALL PRIVILEGES ON weblists.* TO 'weblists_admin'@'localhost';

GRANT GRANT OPTION ON weblists.* TO 'weblists_admin'@'localhost';
