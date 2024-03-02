-- Insert into MotionPicture
INSERT INTO MotionPicture (id, name, rating, production, budget)
VALUES (1, 'The Matrix', 8.7, 'Warner Bros', 63000000);
INSERT INTO MotionPicture (id, name, rating, production, budget)
VALUES (2, 'Inception', 8.8, 'Warner Bros', 160000000);
INSERT INTO MotionPicture (id, name, rating, production, budget)
VALUES (3, 'Interstellar', 8.6, 'Paramount Pictures', 165000000);

-- Insert into User
INSERT INTO User (email, name, age)
VALUES ('john.doe@example.com', 'John Doe', 30);
INSERT INTO User (email, name, age)
VALUES ('jane.smith@example.com', 'Jane Smith', 25);

-- Insert into Likes
INSERT INTO Likes (uemail, mpid)
VALUES ('john.doe@example.com', 1);
INSERT INTO Likes (uemail, mpid)
VALUES ('john.doe@example.com', 2);
INSERT INTO Likes (uemail, mpid)
VALUES ('jane.smith@example.com', 2);

-- Insert into Movie
INSERT INTO Movie (mpid, boxoffice_collection)
VALUES (1, 463517383);
INSERT INTO Movie (mpid, boxoffice_collection)
VALUES (2, 836836967);

-- Insert into Series
INSERT INTO Series (mpid, season_count)
VALUES (3, 1);

-- Insert into People
INSERT INTO People (name, nationality, dob, gender)
VALUES ('Keanu Reeves', 'Canadian', '1964-09-02', 'Male');
INSERT INTO People (name, nationality, dob, gender)
VALUES ('Leonardo DiCaprio', 'American', '1974-11-11', 'Male');
INSERT INTO People (name, nationality, dob, gender)
VALUES ('Christopher Nolan', 'British', '1970-07-30', 'Male');

-- Insert into Role
INSERT INTO Role (mpid, pid, role_name)
VALUES (1, 1, 'Actor');
INSERT INTO Role (mpid, pid, role_name)
VALUES (2, 2, 'Actor');
INSERT INTO Role (mpid, pid, role_name)
VALUES (3, 3, 'Director');

-- Insert into Award
INSERT INTO Award (mpid, pid, award_name, award_year)
VALUES (1, 1, 'Best Actor', 2000);
INSERT INTO Award (mpid, pid, award_name, award_year)
VALUES (2, 2, 'Best Actor', 2016);

-- Insert into Genre
INSERT INTO Genre (mpid, genre_name)
VALUES (1, 'Action');
INSERT INTO Genre (mpid, genre_name)
VALUES (2, 'Sci-Fi');
INSERT INTO Genre (mpid, genre_name)
VALUES (3, 'Sci-Fi');

-- Insert into Location
INSERT INTO Location (mpid, zip, city, country)
VALUES (1, '10001', 'New York', 'USA');
INSERT INTO Location (mpid, zip, city, country)
VALUES (2, '90001', 'Los Angeles', 'USA');
INSERT INTO Location (mpid, zip, city, country)
VALUES (3, 'SW1A', 'London', 'UK');
