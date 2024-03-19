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
    <h1 style="text-align:center">COSI 127b</h1>
    <h3 style="text-align:center">IMDB "Clone"</h3>
</div>

<div class="container">
    <?php echo Tabs([
            Tab("motion-picture", "Motion Pictures"),
            Tab("people", "People"),
            Tab("likes", "Likes")
        ]
    ) ?>
    <div class="tab-content">
        <div class="tab-pane fade <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "motion-picture") echo "show active" ?>"
             id="motion-picture-search" role="tabpanel"
             aria-labelledby="motion-picture-search-tab" tabindex="0">
            <?php echo Form("motion-picture", [
                TextInput("title", "Title"),
                DropDown("type", "Type", ["Movie", "TV Show"]),
                TextInput("genre", "Genre"),
                TextInput("rating-start", "Rated from"),
                TextInput("rating-end", "to"),
                TextInput("production", "Production")])
            ?>
        </div>
        <div class="tab-pane fade <?php if (isset($_GET["tab"]) && $_GET["tab"] == "people") echo "show active" ?>"
             id="people-search" role="tabpanel" aria-labelledby="people-search-tab"
             tabindex="1">
            <?php echo Form("people", [
                TextInput("name", "Name"),
                TextInput("worked-in", "Worked in"),
                TextInput("role", "Role"),
                TextInput("award", "Award")
            ]) ?>
        </div>
        <div class="tab-pane fade <?php if (isset($_GET["tab"]) && $_GET["tab"] == "likes") echo "show active" ?>"
             id="likes-search" role="tabpanel" aria-labelledby="likes-search-tab"
             tabindex="1">
            <form method="post" action="index.php">
                <div class="input-group mb-3">
                    <label for="email" class="input-group-text">Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>">

                    <button class="btn btn-outline-secondary" type="submit" name="likes-submitted"
                            formaction="?tab=likes">Your likes
                    </button>
                </div>
                <div class="input-group mb-3">
                    <label for="title-like-toggle" class="input-group-text">Motion picture</label>
                    <input type="text" class="form-control" placeholder="Title" id="title-like-toggle"
                           name="motion-picture"
                           value="<?php if (isset($_POST['motion-picture'])) echo $_POST['motion-picture'] ?>">

                    <button class="btn btn-outline-secondary" type="submit" name="toggle-like" formaction="?tab=likes">
                        Like motion picture
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <?php
    require_once "query_builder.php";

    function dbg($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    dbg($_POST);

    // SQL CONNECTIONS
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

    $qb = new QueryBuilder($conn);
    // Handle submit buttons
    if (isset($_POST['motion-picture-submitted'])) {
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->leftJoin('Genre G', 'M.id = G.mpid')->group('G.genre_name', 'genres');

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
        $table_header = "<tr>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Rating</th>
                            <th class='col-md-2'>Production</th>
                            <th class='col-md-2'>Budget</th>
                            <th class='col-md-2'>Genre(s)</th>
                        </tr>";
    } else if (isset($_POST['people-submitted'])) {
        $qb = $qb->select('P.name', 'P.nationality', 'P.dob', 'P.gender')
            ->from('People P')
            ->groupBy('P.id')
            ->leftJoin('Role R', 'P.id = R.pid')->group('R.role_name', 'roles')
            ->leftJoin('MotionPicture M', 'R.mpid = M.id')->group('M.name', 'motion_pictures')
            ->leftJoin('Award A', 'P.id = A.pid')->group('A.award_name', 'awards');
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
        $table_header = "<tr>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Nationality</th>
                            <th class='col-md-2'>DOB</th>
                            <th class='col-md-2'>Gender</th>
                            <th class='col-md-2'>Roles</th>
                            <th class='col-md-2'>Motion Pictures</th>
                            <th class='col-md-2'>Awards</th>
                        </tr>";
    } else if (isset($_POST['likes-submitted'])) {
//        <input type="text" class="form-control" placeholder="" id="email" name="email" oninput="setEmailFields(this.value)">
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget')
            ->from('MotionPicture M')
            ->groupBy('M.id')
            ->group('G.genre_name', 'genres')
            ->leftJoin('Genre G', 'M.id = G.mpid')
            ->leftJoin('Likes L', 'M.id = L.mpid')
            ->where("L.uemail = :email");
        $qb->params[':email'] = $_POST['email'];

        $query = $qb->build();
        $table_header = "<tr>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Rating</th>
                            <th class='col-md-2'>Production</th>
                            <th class='col-md-2'>Budget</th>
                            <th class='col-md-2'>Genre(s)</th>
                        </tr>";
    } else if (isset($_POST['toggle-like'])) {
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
            ->group('G.genre_name', 'genres')
            ->leftJoin('Genre G', 'M.id = G.mpid')
            ->leftJoin('Likes L', 'M.id = L.mpid')
            ->where("L.uemail = :email");
        $qb->params[':email'] = $_POST['email'];

        $query = $qb->build();
        $table_header = "<tr>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Rating</th>
                            <th class='col-md-2'>Production</th>
                            <th class='col-md-2'>Budget</th>
                            <th class='col-md-2'>Genre(s)</th>
                        </tr>";
    } else {
        $query = $conn->prepare("SELECT * FROM MotionPicture;");
        $table_header = "<tr>
                            <th class='col-md-2'>ID</th>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Rating</th>
                            <th class='col-md-2'>Production</th>
                            <th class='col-md-2'>Budget</th>
                         </tr>";
    }

    // We will now create a table from PHP side
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
        // when fetching, we want to fetch as an associative array that only contains column names, not column names and indexes
        $query->setFetchMode(PDO::FETCH_ASSOC);

        // For each row that we fetched, use the iterator to build a table row on front-end
        foreach (new TableRows(new RecursiveArrayIterator($query->fetchAll())) as $k => $v) {
            echo $v;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br>" . $e->getTraceAsString() . "<br>" . $e->getPrevious();
    }

    echo "</tbody>";
    echo "</table>";

    // Destroy our connection
    $conn = null;
    ?>
</div>
</body>
</html>