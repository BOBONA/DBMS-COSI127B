-- Entity
CREATE TABLE IF NOT EXISTS motion_picture (
	id INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
	name VARCHAR(255) NOT NULL,
	-- genres Genre[]
	production VARCHAR(255) NOT NULL,
	release_date DATETIME NOT NULL,
	budget FLOAT NOT NULL
	-- ratings Rating[]
	-- awards Award[]
	-- shooting_locations ShootingLocation[]
);

-- Entity
CREATE TABLE IF NOT EXISTS movie (
	id INTEGER PRIMARY KEY NOT NULL,
	box_office_collection FLOAT,
  FOREIGN KEY(id) REFERENCES motion_picture(id)
);

-- Entity
CREATE TABLE IF NOT EXISTS tv_series (
	id INTEGER PRIMARY KEY NOT NULL,
	no_seasons INTEGER,
  FOREIGN KEY(id) REFERENCES motion_picture(id)
);

-- Entity
CREATE TABLE IF NOT EXISTS genre (
	id INTEGER PRIMARY KEY NOT NULL,
	name VARCHAR(255) NOT NULL,
	description VARCHAR(255) NOT NULL,
	-- motion_pictures MotionPicture[]
	-- CONSTRAINT UniqueGenre UNIQUE (name, description)
  UNIQUE (name)
);

-- Relation: 
CREATE TABLE IF NOT EXISTS motion_picture_genre_association (
	motion_picture_id INTEGER NOT NULL,	
	genre_id INTEGER NOT NULL,

	PRIMARY KEY (motion_picture_id, genre_id),
  FOREIGN KEY(motion_picture_id) REFERENCES motion_picture(id),
  FOREIGN KEY(genre_id) REFERENCES genre(id)
)

CREATE TABLE IF NOT EXISTS person (
	id INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
	name VARCHAR(255) NOT NULL,
	nationality VARCHAR(255) NOT NULL,
	dob DATE NOT NULL,
	gender VARCHAR(32) NOT NULL,
	-- roles Role[]
	-- awards Award[]

	ADD CONSTRAINT RatingBetween0And10
	CHECK ( rating >= 0 AND rating <= 10 )
);

CREATE TABLE IF NOT EXISTS role (
	person_id INTEGER NOT NULL,
	motion_picture_id INTEGER NOT NULL,
	role VARCHAR(255) NOT NULL,-- should be own table?

	PRIMARY KEY (person_id, motion_picture_id, role),
  FOREIGN KEY(person_id) REFERENCES person(id),
  FOREIGN KEY(motion_picture_id) REFERENCES motion_picture(id)
);

-- CREATE TABLE IF NOT EXISTS rating (
-- 	user_id INTEGER AUTO_INCREMENT NOT NULL,
-- 	motion_picture_id INTEGER NOT NULL,
-- 	val INTEGER NOT NULL,	
--
-- 	PRIMARY KEY (person_id, motion_picture_id),
--   FOREIGN KEY(user_id) REFERENCES user(id),
--   FOREIGN KEY(motion_picture_id) REFERENCES motion_picture(id)
--
-- 	ADD CONSTRAINT ValueBetween0And10
-- 	CHECK ( val >= 0 AND val <= 10 )
-- )

-- only one award per person per movie
CREATE TABLE IF NOT EXISTS award (
	person_id INTEGER NOT NULL,
	motion_picture_id INTEGER NOT NULL,
	name VARCHAR(255) NOT NULL,
	year_received INTEGER NOT NULL, -- constraint?

	PRIMARY KEY (person_id, motion_picture_id, name),
  FOREIGN KEY(person_id) REFERENCES person(id),
  FOREIGN KEY(motion_picture_id) REFERENCES motion_picture(id)
);

CREATE TABLE IF NOT EXISTS shooting_location (
	motion_picture_id INTEGER NOT NULL,
	name VARCHAR(255) NOT NULL,
	city VARCHAR(255) NOT NULL,
	country VARCHAR(255) NOT NULL,

  FOREIGN KEY(motion_picture_id) REFERENCES motion_picture(id),
	PRIMARY KEY (
		motion_picture_id, name, city, country
	)
);

CREATE TABLE IF NOT EXISTS user (
	id INTEGER PRIMARY KEY NOT NULL
	email VARCHAR(255) NOT NULL UNIQUE
	name VARCHAR(255) NOT NULL,
	age INTEGER NOT NULL -- positive
);

CREATE TABLE IF NOT EXISTS motion_picture_like (
	user_id INTEGER NOT NULL,
	motion_picture_id INTEGER NOT NULL,
	PRIMARY KEY (user_id, motion_picture_id)
);

-- TODO: Indexes
-- TODO: Views


-- is rating a table or just a number (users rate motion_pictures)
