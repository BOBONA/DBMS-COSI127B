CREATE TABLE IF NOT EXISTS `MotionPicture`
(
    id         INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name       VARCHAR(255)                       NOT NULL,
    rating     FLOAT                              NOT NULL,
    production VARCHAR(255)                       NOT NULL,
    budget     FLOAT                              NOT NULL

    -- CONSTRAINT RatingBetween0And10 CHECK (rating >= 0 AND rating <= 10)
);

CREATE TABLE IF NOT EXISTS `User`
(
    email VARCHAR(255) PRIMARY KEY NOT NULL,
    name  VARCHAR(255)             NOT NULL,
    age   INTEGER                  NOT NULL

    -- CONSTRAINT PositiveAge CHECK (age >= 0)
);

CREATE TABLE IF NOT EXISTS `Likes`
(
    uemail VARCHAR(255) NOT NULL,
    mpid   INTEGER      NOT NULL,

    PRIMARY KEY (uemail, mpid),
    FOREIGN KEY (uemail) REFERENCES User (email),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id)
);

CREATE TABLE IF NOT EXISTS `Movie`
(
    mpid                 INTEGER NOT NULL,
    boxoffice_collection INTEGER NOT NULL,

    PRIMARY KEY (mpid),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id)
);

CREATE TABLE IF NOT EXISTS `Series`
(
    mpid         INTEGER NOT NULL,
    season_count INTEGER NOT NULL,

    PRIMARY KEY (mpid),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id)
);

CREATE TABLE IF NOT EXISTS `People`
(
    id          INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name        VARCHAR(255)                       NOT NULL,
    nationality VARCHAR(255)                       NOT NULL,
    dob         DATE                               NOT NULL,
    gender      VARCHAR(32)                        NOT NULL
);

CREATE TABLE IF NOT EXISTS `Role`
(
    mpid      INTEGER      NOT NULL,
    pid       INTEGER      NOT NULL,
    role_name VARCHAR(255) NOT NULL,

    PRIMARY KEY (mpid, pid, role_name),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id),
    FOREIGN KEY (pid) REFERENCES People (id)
);

CREATE TABLE IF NOT EXISTS `Award`
(
    mpid       INTEGER      NOT NULL,
    pid        INTEGER      NOT NULL,
    award_name VARCHAR(255) NOT NULL,
    award_year INTEGER      NOT NULL,

    PRIMARY KEY (mpid, pid, award_name, award_year),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id),
    FOREIGN KEY (pid) REFERENCES People (id)
);

CREATE TABLE IF NOT EXISTS `Genre`
(
    mpid       INTEGER     NOT NULL,
    genre_name VARCHAR(32) NOT NULL,

    PRIMARY KEY (mpid, genre_name),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id)
);

CREATE TABLE IF NOT EXISTS `Location`
(
    mpid    INTEGER     NOT NULL,
    zip     VARCHAR(32) NOT NULL,
    city    VARCHAR(64) NOT NULL,
    country VARCHAR(64) NOT NULL,

    PRIMARY KEY (mpid, zip),
    FOREIGN KEY (mpid) REFERENCES MotionPicture (id)
);
