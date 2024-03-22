<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"
            integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSI 127b</title>
</head>

<body>
<?php
require_once "query_builder.php";
require_once "components.php";
?>
<script>
    function setTab(value) {
        history.pushState({}, "", window.location.pathname + "?tab=" + value)
    }

    console.log("hello");

    // stop form from refreshing page and losing state
    document.getElementsByName("form").forEach(function (form) {
        console.log("hi")
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData).toString();
            const action = form.getAttribute("formaction");
            fetch(action, {
                method: "POST",
                body: searchParams,
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                }
            }).then(response => response.text()).then(text => {
                document.querySelector("body").innerHTML = text;
            });
        });
    });
</script>

<div class="container">
    <h1 style="text-align:center">COSI 127B</h1>
    <h3 style="text-align:center">IMDB "Clone"</h3>
</div>

<div class="container">
    <?php echo Tabs([
        Tab("motion-picture", "Motion Pictures", true),
        Tab("people", "People"),
        Tab("likes", "Likes"),
        "<div style='flex: 1;'></div>",
        Tab("motion-picture-misc", "Motion Picture Misc"),
        Tab("people-misc", "People Misc"),
        Tab("meta", "Meta")
    ]) ?>
    <div class="tab-content">
        <?php echo TabContent("motion-picture", [
            Form("motion-picture", "motion-picture", [
                TextInput("title", "Title"),
                DropDown("type", "Type", ["Movie", "TV Show"]),
                TextInput("genre", "Genre"),
                TextInput("rating-start", "Rated from"),
                TextInput("rating-end", "to"),
                TextInput("production", "Production")],
                "Search motion pictures"),
        ], true) ?>
        <?php echo TabContent("people", [
            Form("people", "people", [
                TextInput("name", "Name"),
                TextInput("worked-in", "Worked in"),
                TextInput("role", "Role"),
                TextInput("award", "Award")])
        ]) ?>
        <?php echo TabContent("likes", [
            Form("likes", "likes", [
                TextInput("email", "Email")],
                "Your likes"),
            Form("toggle-like", "likes", [
                TextInput("email", "Email"),
                TextInput("motion-picture", "Motion Picture")],
                "Like motion picture")
        ]) ?>
        <?php echo TabContent("motion-picture-misc", [
            Form("search-by-name", "motion-picture-misc", [
                TextInput("name", "Motion picture name")],
                "Search by name"),
            Form("search-by-shooting-location", "motion-picture-misc", [
                TextInput("shooting-location", "Shooting Location")],
                "Search by country"),
            Form("search-movies-with-more-than-x-likes", "motion-picture-misc", [
                TextInput("likes", "More than"),
                TextInput("age", "likes from users younger than")],
                "Search movies"),
            Form("search-top-2-thriller-movies", "motion-picture-misc", [],
                "Top thriller movies shot only in Boston", true),
            Form("search-higher-rating-than-average-comedy", "motion-picture-misc", [],
                "Movies better than the average comedy", true),
            Form("search-most-involved-movies", "motion-picture-misc", [],
                "Top 5 movies with the most involved people", true)
        ]) ?>
        <?php echo TabContent("people-misc", [
            Form("search-directors-in-zipcode", "people-misc", [
                TextInput("zipcode", "Zipcode")],
                "Search for directed TV series"),
            Form("search-award-winners", "people-misc", [
                TextInput("award-count", "Minimum awards for a single picture in a single year (non-inclusive)")],
                "Search award winners"),
            Form("search-producers", "people-misc", [
                TextInput("box-office-collection", "Minimum box office collection"),
                TextInput("budget", "Maximum budget")],
                "Search for American producers"),
            Form("search-actors-with-multiple-roles", "people-misc", [
                TextInput("rating", "Minimum picture rating")],
                "Search actors with multiple roles"),
            Form("search-youngest-oldest-actors", "people-misc", [],
                "Search youngest and oldest actors to win an award", true),
            Form("search-actors-in-marvel-and-warner-bros", "people-misc", [],
                "Search actors in Marvel and Warner Bros", true),
            Form("search-actors-with-same-birthday", "people-misc", [],
                "Search actors with same birthday", true)
        ]) ?>
        <?php echo TabContent("meta", [
            Form("show-tables", "meta", [], "Show all tables"),
        ]) ?>
    </div>
</div>

<div class="container">
    <?php
    require_once "query_builder.php";

    function dbg($var): void
    {
//        echo "<pre>";
//        var_dump($var);
//        echo "</pre>";
    }

    dbg($_POST);

    // SQL Connections
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "project 1.3";

    // Create ORM connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    } catch (PDOException $err) {
        echo "error creating pdo " . $err->getMessage();
        return;
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle forms
    $qb = new QueryBuilder($conn);
    if (isset($_POST['motion-picture-submitted'])) {
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')->groupCol('G.genre_name', 'genres');

        if (!empty($_POST['title'])) {
            $qb->where("M.name LIKE :title");
            $qb->params[':title'] = "%" . $_POST['title'] . "%";
        }

        // Check if the movie and/or tv show checkboxes are checked
        if (!isset($_POST['search-movies']) || !isset($_POST['search-tv-shows'])) {
            if (isset($_POST['search-movies'])) {
                $qb->innerJoin('Movie', 'M.id = Movie.mpid');
            } else if (isset($_POST['search-tv-shows'])) {
                $qb->innerJoin('Series', 'M.id = Series.mpid');
            }
        }

        if (!empty($_POST['genre'])) {
            $qb->where('G.genre_name LIKE :genre');
            $qb->params[':genre'] = "%" . $_POST['genre'] . "%";
        }
        if (!empty($_POST['rating-start'])) {
            $qb->where("M.rating >= :rating_start");
            $qb->params[':rating_start'] = intval($_POST['rating-start']);
        }
        if (!empty($_POST['rating-end'])) {
            $qb->where("M.rating <= :rating_end");
            $qb->params[':rating_end'] = intval($_POST['rating-end']);
        }
        if (!empty($_POST['production'])) {
            $qb->where("M.production LIKE :production");
            $qb->params[':production'] = "%" . $_POST['production'] . "%";
        }

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating", "Production", "Budget", "Genre(s)"]);
    } /* <format trick> */
    else if (isset($_POST['people-submitted'])) {
        $qb = $qb->select('P.name', 'P.nationality', 'P.dob', 'P.gender')
            ->from('People P')
            ->groupBy('P.id')
            ->leftJoin('Role R', 'P.id = R.pid')->groupCol('R.role_name', 'roles')
            ->leftJoin('MotionPicture M', 'R.mpid = M.id')->groupCol('M.name', 'motion_pictures')
            ->leftJoin('Award A', 'P.id = A.pid')->groupCol('A.award_name', 'awards');
        if (!empty($_POST['name'])) {
            $qb->where("P.name LIKE :name");
            $qb->params[':name'] = "%" . $_POST['name'] . "%";
        }
        if (!empty($_POST['worked-in'])) {
            $qb->where("M.name LIKE :worked_in");
            $qb->params[':worked_in'] = "%" . $_POST['worked-in'] . "%";
        }
        if (!empty($_POST['role'])) {
            $qb->where("R.role_name LIKE :role");
            $qb->params[':role'] = "%" . $_POST['role'] . "%";
        }
        if (!empty($_POST['award'])) {
            $qb->where("A.award_name LIKE :award");
            $qb->params[':award'] = "%" . $_POST['award'] . "%";
        }

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Nationality", "DOB", "Gender", "Roles", "Motion Pictures", "Awards"]);
    } /* <format trick> */
    else if (isset($_POST['likes-submitted'])) {
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')->groupCol('G.genre_name', 'genres')
            ->leftJoin('Likes L', 'M.id = L.mpid')
            ->where("L.uemail = :email");
        $qb->params[':email'] = $_POST['email'];

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating", "Production", "Budget", "Genre(s)"]);
    } /* <format trick> */
    else if (isset($_POST['toggle-like-submitted'])) {
        if (empty($_POST['email']) || empty($_POST['motion-picture'])) {
            echo "Please enter an email and a motion picture to like or unlike";
            return;
        }
        try {
            // create user if they dont exist
            $email = $_POST['email'];
            $name = explode("@", $email, 1)[0] ?? "Anonymous";
            dbg($name);
            $age = -1;
            $createUser = $conn->prepare("INSERT INTO User (email, name, age) VALUES (:email, :name, :age ) ON DUPLICATE KEY UPDATE email = email;");
            $createUser->bindParam(":email", $email);
            $createUser->bindParam(":name", $name);
            $createUser->bindParam(":age", $age);
            $createUser->execute();

            // check if user has liked the motion picture
            $hasLiked = $conn->prepare("SELECT mpid FROM Likes WHERE uemail = :email AND mpid = (SELECT id FROM MotionPicture WHERE name = :mpname);");
            $hasLiked->bindParam(":email", $_POST['email']);
            $hasLiked->bindParam(":mpname", $_POST['motion-picture']);
            $hasLiked->execute();

            if ($hasLiked->rowCount() > 0) {
                $mpid = $hasLiked->fetch(PDO::FETCH_ASSOC)['mpid'];
                $unlike = $conn->prepare("DELETE FROM Likes WHERE uemail = :email AND mpid = :mpid;");
                $unlike->bindParam(":email", $_POST['email']);
                $unlike->bindParam(":mpid", $mpid);

                $unlike->execute();
            } else {
                $getMpid = $conn->prepare("SELECT id FROM MotionPicture WHERE name = :mpname");
                $getMpid->bindParam(":mpname", $_POST['motion-picture']);
                $getMpid->execute();
                $mpid = $getMpid->fetch(PDO::FETCH_ASSOC);
                if (empty($mpid)) {
                    echo "Motion picture not found";
                    return;
                }
                $mpid = $mpid['id'];

                $like = $conn->prepare("INSERT INTO Likes (uemail, mpid) VALUES (:email, :mpid);");
                $like->bindParam(":email", $_POST['email']);
                $like->bindParam(":mpid", $mpid);
                $like->execute();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>" . $e->getTraceAsString() . "<br>" . $e->getPrevious();
        }

        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')->groupCol('G.genre_name', 'genres')
            ->leftJoin('Likes L', 'M.id = L.mpid')
            ->where("L.uemail = :email");
        $qb->params[':email'] = $_POST['email'];

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating", "Production", "Budget", "Genre(s)"]);
    } /* <format trick> */
    else if (isset($_POST['search-by-name-submitted'])) {
        // 2. Search Motion Picture by Motion picture name (parameterized). List the movie name, rating, production and budget.
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')->groupCol('G.genre_name', 'genres')
            ->where("M.name LIKE :name");
        $qb->params[':name'] = "%" . $_POST['name'] . "%";

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating", "Production", "Budget", "Genre(s)"]);
    } /* <format trick> */
    else if (isset($_POST['search-by-shooting-location-submitted'])) {
        // 4. Search motion pictures by their shooting location country (parameterized). List only the
        // motion picture names without any duplicates.
        $qb = $qb->select('M.name')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Location S', 'M.id = S.mpid')
            ->where("S.country LIKE :location");
        $qb->params[':location'] = "%" . $_POST['shooting-location'] . "%";

        $query = $qb->build();
        $table_header = TableHeader(["Name"]);
    }/* <format trick> */
    else if (isset($_POST['search-movies-with-more-than-x-likes-submitted'])) {
        // 11. Find all the movies with more than “X” (parameterized) likes by users of age less than “Y”
        // (parameterized). List the movie names and the number of likes by those age-group users.
        $qb = $qb->select('M.name', 'COUNT(L.uemail) as likes')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Likes L', 'M.id = L.mpid')
            ->leftJoin('User U', 'L.uemail = U.email')
            ->where("U.age < :age")
            ->having("likes > :likes");
        $qb->params[':age'] = $_POST['age'];
        $qb->params[':likes'] = $_POST['likes'];

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Likes"]);
    } /* <format trick> */
    else if (isset($_POST['search-top-2-thriller-movies-submitted'])) {
        // 10. Find the top 2 rates thriller movies (genre is thriller) that were shot exclusively in Boston.
        // This means that the movie cannot have any other shooting location. List the movie names
        // and their ratings.
        $qb = $qb->select('M.name', 'M.rating')
            ->from('MotionPicture M')
            ->leftJoin('Location L', 'M.id = L.mpid')
            ->leftJoin('Genre G', 'M.id = G.mpid')
            ->where("G.genre_name = 'Thriller'")
            ->where("L.city = 'Boston'")
            ->where("NOT EXISTS (SELECT * FROM Location L2 WHERE L2.mpid = M.id
            AND L2.city != 'Boston')")
            ->groupBy('M.id')
            ->orderBy("M.rating", "DESC")
            ->limit(2);

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating"]);
    } /* <format trick> */
    else if (isset($_POST['search-higher-rating-than-average-comedy-submitted'])) {
        // 13. Find the motion pictures that have a higher rating than the average rating of all comedy
        // (genre) motion pictures. Show the names and ratings in descending order of ratings.
        $qb = $qb->select('M.name', 'M.rating')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')
            ->where("G.genre_name = 'Comedy'")
            ->having("M.rating > (SELECT AVG(M2.rating) FROM MotionPicture M2 LEFT JOIN Genre G2 ON M2.id = G2.mpid WHERE G2.genre_name = 'Comedy')")
            ->orderBy("M.rating", "DESC");

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Rating"]);
    } /* <format trick> */
    else if (isset($_POST['search-most-involved-movies-submitted'])) {
        // 14. Find the top 5 movies with the highest number of people playing a role in that movie. Show
        // the movie name, people count and role count for the movies.
        $qb = $qb->select('M.name', 'COUNT(DISTINCT P.id) as people_count', 'COUNT(DISTINCT R.role_name) as role_count')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Role R', 'M.id = R.mpid')
            ->leftJoin('People P', 'R.pid = P.id')
            ->orderBy("people_count", "DESC")
            ->limit(5);

        $query = $qb->build();
        $table_header = TableHeader(["Name", "People Count", "Role Count"]);
    } /* <format trick> */
    else if (isset($_POST['search-directors-in-zipcode-submitted'])) {
        // 5. List all directors who have directed TV series shot in a specific zip code (parameterized).
        $qb = $qb->select("P.name as Director")
            ->from("Location L")
            ->innerJoin("Role R", "L.mpid = R.mpid")
            ->innerJoin("People P", "P.id = R.pid")
            ->innerJoin("Series S", "R.mpid = S.mpid")
            ->innerJoin("MotionPicture M", "M.id = R.mpid")->groupCol("M.name", "Series");
        if (!empty($_POST['zipcode'])) {
            $qb->where("L.zip = :zipcode");
            $qb->params[':zipcode'] = $_POST['zipcode'];
        }
        $qb = $qb->where("R.role_name = 'Director'")
            ->groupBy("P.id");

        $query = $qb->build();
        $table_header = TableHeader(["Director", "TV Series"]);
        // List the director name and TV series name only without duplicates.

    } /* <format trick> */
    else if (isset($_POST['search-award-winners-submitted'])) {
        // 6. Find the people who have received more than “k” (parameterized) awards for a single motion
        // picture in the same year. List the person name, motion picture name, award year and award
        // count.
        // TODO fix gorupby groupCol
        $qb = $qb->select("P.name", "M.name as MotionPicture", "COUNT(A.award_name) as AwardCount",
            "A.award_year"
        )
            ->from("Award A")
            ->innerJoin("People P", "A.pid = P.id")
            ->groupCol("A.award_name", "AwardName")
            ->innerJoin("MotionPicture M", "A.mpid = M.id")
            ->orderBy("AwardCount", "DESC")
            ->groupBy("P.id", "M.id", "A.award_year")
            ->having("COUNT(A.award_name) > :award_count");
        if (!empty($_POST['award-count'])) {
            $qb->params[':award_count'] = $_POST['award-count'];
        } else {
            $qb->params[':award_count'] = 0;
        }

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Motion Picture", "Award Count",  "Award Year", "Award name(s)"]);
    } /* <format trick> */
    else if (isset($_POST['search-producers-submitted'])) {
        // 8. Find the American Producers who had a box office collection of more than or equal to “X”
        // (parameterized) with a budget less than or equal to “Y” (parameterized). List the producer
        // name, movie name, box office collection and budget
        $qb = $qb->select("P.name", "M.name as MotionPicture", "Mo.boxoffice_collection", "M.budget")
            ->from("People P")
            ->innerJoin("Role R", "P.id = R.pid")
            ->innerJoin("MotionPicture M", "R.mpid = M.id")
            ->innerJoin("Movie Mo", "Mo.mpid = M.id")
            ->where("P.nationality = 'USA'")
            ->where("R.role_name = 'Producer'");
        if (!empty($_POST['box-office-collection'])) {
            $qb->where("M.box_office_collection >= :box_office_collection");
            $qb->params[':box_office_collection'] = $_POST['box-office-collection'];
        }
        if (!empty($_POST['budget'])) {
            $qb->where("M.budget <= :budget");
            $qb->params[':budget'] = $_POST['budget'];
        }

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Motion Picture", "Box Office Collection", "Budget"]);
    } /* <format trick> */
    else if (isset($_POST['search-actors-with-multiple-roles-submitted'])) {
        // 9. List the people who have played multiple roles in a motion picture where the rating is more
        // than “X” (parameterized). List the person’s name, motion picture name and count of number
        // of roles for that particular motion picture.
        $qb = $qb->select("P.name", "M.name as MotionPicture", "COUNT(R.role_name) as RoleCount")
            ->from("Role R")
            ->innerJoin("People P", "R.pid = P.id")
            ->innerJoin("MotionPicture M", "R.mpid = M.id");

        if (!empty($_POST['rating'])) {
            $qb->params[':rating'] = $_POST['rating'];
            $qb = $qb->where("M.rating > :rating");
        }

        $qb = $qb->groupBy("P.id", "M.id")
            ->having("RoleCount > 1");

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Motion Picture", "Role Count"]);
    } /* <format trick> */
    else if (isset($_POST['search-youngest-oldest-actors-submitted'])) {
        // 7. Find the youngest and oldest actors to win at least one award. List the actor names and their
        // age (at the time they received the award). The age should be computed from the person’s
        // date of birth to the award-winning year only. In case of a tie, list all of them.
        $qb = $qb->select("P.name", "A.award_year - YEAR(P.dob) as age")
            ->from("Award A")
            ->innerJoin("People P", "A.pid = P.id")
            ->where("
                A.award_year - YEAR(P.dob) = (SELECT MIN(A2.award_year - YEAR(P2.dob)) FROM Award A2 INNER JOIN People P2 ON A2.pid = P2.id) OR 
                A.award_year - YEAR(P.dob) = (SELECT MAX(A3.award_year - YEAR(P3.dob)) FROM Award A3 INNER JOIN People P3 ON A3.pid = P3.id)
            ")
            ->orderBy("age", "ASC");

        $query = $qb->build();
        $table_header = TableHeader(["Name", "Age"]);
    } /* <format trick> */
    else if (isset($_POST['search-actors-in-marvel-and-warner-bros-submitted'])) {
        // 12. Find the actors who have played a role in both “Marvel” and “Warner Bros” productions.
        // List the actor names and the corresponding motion picture names.
        $sql = "
        SELECT 
            P.name, 
            GROUP_CONCAT(DISTINCT M.name SEPARATOR ', ') as MotionPicture
        FROM People P 
        INNER JOIN Role R ON P.id = R.pid AND R.role_name = 'Actor'
        INNER JOIN MotionPicture M ON R.mpid = M.id
        WHERE M.production = 'Marvel' OR M.production = 'Warner Bros'
        GROUP BY P.id 
        HAVING COUNT(DISTINCT M.production) > 1;
        
        ";

        $query = $conn->prepare($sql);
        dbg($query);

        $table_header = TableHeader(["Name", "Motion Picture"]);
    } /* <format trick> */
    else if (isset($_POST['search-actors-with-same-birthday-submitted'])) {
        // 15. Find actors who share the same birthday. List the actor names (actor 1, actor 2) and their
        // common birthday.
        $qb = $qb->select("P1.name as Actor1", "P2.name as Actor2", "P1.dob as Birthday")
            ->from("People P1")
            ->innerJoin("People P2", "P1.dob = P2.dob")
            ->where("P1.id < P2.id")
            ->orderBy("P1.name", "ASC")
            ->groupBy("P1.id", "P2.id");

        $query = $qb->build();
        $table_header = TableHeader(["Actor 1", "Actor 2", "Birthday"]);

    }/* <format trick> */
    else if (isset($_POST['show-tables-submitted'])) {
        // 1. List all the tables in the database.
        $query = $conn->prepare("SHOW TABLES;");
        $table_header = TableHeader(["Tables"]);
    } /* <format trick> */
    else {
        $query = $conn->prepare("SELECT * FROM MotionPicture;");
        $table_header = TableHeader(["ID", "Name", "Rating", "Production", "Budget"]);
    }

    // Create a table with the results of the query
    echo "<table class='table table-md table-bordered'>";
    echo "<thead class='thead-dark' style='text-align: center'>";
    echo $table_header;
    echo "</thead>";
    echo "<tbody>";

    // Generic table builder. It will automatically build table data rows irrespective of result
    class TableRows extends RecursiveIteratorIterator
    {
        function __construct($it)
        {
            parent::__construct($it, self::LEAVES_ONLY);
        }

        function current(): string
        {
            return "<td style='text-align:center'>" . parent::current() . "</td>";
        }

        function beginChildren(): void
        {
            echo "<tr>";
        }

        function endChildren(): void
        {
            echo "</tr>" . "\n";
        }
    }

    try {
        dbg($query);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC); // fetch as an associative array

        // Build a table row for each row in the result
        foreach (new TableRows(new RecursiveArrayIterator($query->fetchAll())) as $k => $v) {
            echo $v;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br>" . $e->getTraceAsString() . "<br>" . $e->getPrevious();
    }

    echo "</tbody>";
    echo "</table>";

    // Destroy the connection
    $conn = null;
    ?>
</div>
</body>
</html>