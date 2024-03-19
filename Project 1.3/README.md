# IMBD Clone

## Supported Queries

### Motion Pictures

- Search by _name_
  - Output movie name, rating, production, budget
- Search by shooting location
  - Output name
- Search the top 2 rated thriller movies shot in Boston
  - Output name, rating
- Search the movies with more than _X_ likes by users younger than _Y_
  - Output name, number of likes by users younger than _Y_
- Search for motion pictures with a higher rating than the average rating of comedy motion pictures
  - Output name, descending rating
- Search for the top 5 movies with the highest number of involved people
  - Output movie name, people count, role count

### People

- Search directors who directed TV series in a _zipcode_
  - Output name, TV series name
- Search people who have received more than _k_ awards for a single motion picture in a single year
  - Output name, motion picture name, award year, award count
- List the youngest and oldest actors (by award-winning year) to win an award
  - Output name, age (at time of award)
- List the American Producers who had a box office collection >= _X_ with a budget <= _Y_
  - Output name, movie name, box office collection, budget
- List people who have played multiple roles in a motion picture where the rating is more than _X_
  - Output name, motion picture name, number of roles
- List the actors who have played a role in both "Marvel" and "Warner Bros" productions
  - Output actor names, motion picture names
- List all actor pairs with the same birthday

### Likes

- Search liked by _email_
   - Output movie name, rating, production, budget
- Functionality to like a movie

### Meta

- Output all tables