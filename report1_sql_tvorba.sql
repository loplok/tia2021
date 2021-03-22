-- zacal som az od 4tej tabulky, lebo tie predtym som vytvoril
-- nasleduju vytvorenia tabuliek, nizsie su riesene FK a alterovanie tabuliek

CREATE TABLE IF NOT EXISTS `banned_users` (
`ban_id` INT AUTO_INCREMENT PRIMARY KEY,
`banned_username` VARCHAR(50) NOT NULL,
`banned_when` DATETIME DEFAULT CURRENT_TIMESTAMP,
`banned_by_who` VARCHAR(50) NOT NULL,
`group_name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `feed_post` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`poster_username` VARCHAR(50) NOT NULL,
`text` TEXT,
`posted_when` DATETIME DEFAULT CURRENT_TIMESTAMP
 ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `group_post` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`username` VARCHAR(50) NOT NULL,
`groups_id` INTEGER NOT NULL,
`text` TEXT,
`posted_when` DATETIME DEFAULT CURRENT_TIMESTAMP
 ) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `comments` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`group_post_id` INTEGER,
`comment_username` VARCHAR(50) NOT NULL,
`text` TEXT
 ) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `likes` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`post_liked_id` INTEGER,
`liked_by_username` VARCHAR(50) NOT NULL
 ) ENGINE=InnoDB;


-- tu sa riesia FKs a nejake ine alteracie

-- banned users FKs
ALTER TABLE `banned_users`
ADD CONSTRAINT FK_banned_username
FOREIGN KEY (banned_username) REFERENCES users(username);

ALTER TABLE `banned_users`
ADD CONSTRAINT FK_banned_by_who
FOREIGN KEY (banned_by_who) REFERENCES users(username);

ALTER TABLE `banned_users`
ADD CONSTRAINT FK_banned_users_groupname
FOREIGN KEY (groupname) REFERENCES groups(groupname);


-- feed_post FKs
ALTER TABLE `feed_post`
ADD CONSTRAINT FK_poster_username
FOREIGN KEY (poster_username) REFERENCES users(username);


-- likes FKs
ALTER TABLE `likes`
ADD CONSTRAINT FK_post_liked_id
FOREIGN KEY (post_liked_id) REFERENCES group_post(id);

ALTER TABLE `likes`
ADD CONSTRAINT FK_liked_by_username
FOREIGN KEY (liked_by_username) REFERENCES users(username);


-- comments FKs
ALTER TABLE `comments`
ADD CONSTRAINT FK_group_post_id
FOREIGN KEY (group_post_id) REFERENCES group_post(id);

ALTER TABLE `comments`
ADD CONSTRAINT FK_comment_username
FOREIGN KEY (comment_username) REFERENCES users(username);


-- library FKs
ALTER TABLE `group_post`
ADD CONSTRAINT FK_library_username
FOREIGN KEY (username) REFERENCES users(username);


-- group_post FKs
ALTER TABLE `group_post`
ADD CONSTRAINT FK_username
FOREIGN KEY (username) REFERENCES users(username);

ALTER TABLE `group_post`
ADD CONSTRAINT FK_groups_id
FOREIGN KEY (groups_id) REFERENCES users_groups(id);


-- users_groups FKs
ALTER TABLE `users_groups`
ADD CONSTRAINT FK_users_groups_username
FOREIGN KEY (username) REFERENCES users(username);

ALTER TABLE `users_groups`
ADD CONSTRAINT FK_users_groups_groupname
FOREIGN KEY (groupname) REFERENCES groups(groupname);





