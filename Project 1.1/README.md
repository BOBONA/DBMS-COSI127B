# DBMS-COSI127B
## ER Diagram Details
This project's [ER diagram](<ER Diagram.drawio>) was made through [draw.io](https://draw.io/)
#### Primary keys
- Motion Picture: id
- Genre: id
- User: id
- Person: id
- Role: role
- Award: name
- Location: (name, city, country)
#### Candidate Keys
- Motion Picture: potentially some combination of (name, production, release date)
- Genre: name
- User: potentially (email)
#### Key Constraints
- Movie, Series ISA Motion Picture: covering constraint (yes), overlap constraint (disallowed)
#### Assumptions
- The system stores the rating of a movie as a single float (0-10), not as individual ratings.
