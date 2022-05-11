USE weblists;

/* 1) Create the tables */
CREATE TABLE list (
	uuid        VARCHAR(36) NOT NULL PRIMARY KEY
		COMMENT 'A full (RFC 4122) UUID stored as a string. Perhaps for efficiency we should store as BINARY(16) per https://dev.mysql.com/blog-archive/storing-uuid-values-in-mysql-tables/ but for now let''s just make things simpler.',
	create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		COMMENT 'The date this list was created. Immutable.',
	name        VARCHAR(200)
		COMMENT 'The name of the list. USER-INPUTTED THEREFORE UNSAFE.',
	description VARCHAR(4000)
		COMMENT 'A description of this list. USER-INPUTTED THEREFORE UNSAFE.'
) COMMENT='A list. This is the central table from which everything else branches.';

CREATE TABLE item (
	id          INT NOT NULL PRIMARY KEY AUTO_INCREMENT
		COMMENT 'Plain PK. Maybe do user-specific items later.',
	name        VARCHAR(200) NOT NULL
		COMMENT 'The item''s name. USER-INPUTTED THEREFORE UNSAFE.',
	description VARCHAR(4000)
		COMMENT 'A description of this item. USER-INPUTTED THEREFORE UNSAFE.'
) COMMENT='An item. Items are added to lists.';

CREATE TABLE list_item (
	list_uuid     VARCHAR(36) NOT NULL,
	item_id       INT NOT NULL,
	sort_idx      INT NOT NULL DEFAULT 0
		COMMENT 'The item''s order in the list. Updated by in-app logic.',
	quantity      INT
		COMMENT 'The item count.',
	quantity_unit VARCHAR(20)
		COMMENT 'The item count units (eg: "count", "dozen", "gallon", "oz", "kg"). USER-INPUTTED THEREFORE UNSAFE.',
	marked        BOOLEAN NOT NULL DEFAULT FALSE
		COMMENT 'Whether or not this item is "marked". May change this to a dictionary if we want to mark items in different ways.',

	FOREIGN KEY (list_uuid) REFERENCES list(uuid),
	FOREIGN KEY (item_id)   REFERENCES item(id)
) COMMENT='An item in a list.';

/* 2) Grant privileges to _user */
GRANT SELECT, INSERT, UPDATE, DELETE
ON weblists.*
TO 'weblists_user'@'localhost';
