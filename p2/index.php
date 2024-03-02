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
<div class="container">
    <h1 style="text-align:center">COSI 127b</h1>
    <h3 style="text-align:center">IMDB "Clone"</h3>
</div>

<div class="container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "motion-picture") echo "active" ?>"
                    id="motion-picture-tab" data-bs-toggle="tab"
                    data-bs-target="#motion-picture-search" type="button" role="tab"
                    aria-controls="motion-picture-search" onclick="setTab('motion-picture')">Motion Pictures
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php if (isset($_GET["tab"]) && $_GET["tab"] == "people") echo "active" ?>"
                    id="people-tab" data-bs-toggle="tab" data-bs-target="#people-search"
                    type="button" role="tab" aria-controls="people-search" onclick="setTab('people')">People
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php if (isset($_GET["tab"]) && $_GET["tab"] == "likes") echo "active" ?>"
                    id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes-search"
                    type="button" role="tab" aria-controls="likes-search" onclick="setTab('likes')">Likes
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "motion-picture") echo "show active" ?>"
             id="motion-picture-search" role="tabpanel"
             aria-labelledby="motion-picture-search-tab" tabindex="0">
            <form method="post" action="index.php">
                <div class="input-group mb-3">
                    <label for="title" class="input-group-text">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="" name="title">

                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Type
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item form-check">
                            <input class="form-check-input mx-1" type="checkbox" value="" id="flexCheckDefault"
                                   name="search-movies">
                            <label class="form-check-label" for="flexCheckDefault">Movie</label>
                        </li>
                        <li class="dropdown-item form-check">
                            <input class="form-check-input mx-1" type="checkbox" value="" id="flexCheckDefault"
                                   name="search-tv-shows">
                            <label class="form-check-label" for="flexCheckDefault">TV Show</label>
                        </li>
                    </ul>

                    <label for="genre" class="input-group-text">Genre</label>
                    <input type="text" class="form-control" id="genre" placeholder="" name="genre">

                    <label for="rating-start" class="input-group-text">Rated from</label>
                    <input type="number" class="form-control" placeholder="1" name="rating-start" id="rating-start">
                    <label for="rating-end" class="input-group-text">to</label>
                    <input type="number" class="form-control" placeholder="10" name="rating-end" id="rating-end">

                    <label for="production" class="input-group-text">Production</label>
                    <input type="text" class="form-control" id="title" placeholder="" name="production">

                    <button class="btn btn-outline-secondary" type="submit" name="motion-picture-submitted"
                            formaction="?tab=motion-picture">Search motion pictures
                    </button>
                </div>
            </form>
        </div>
        <div class="tab-pane fade <?php if (isset($_GET["tab"]) && $_GET["tab"] == "people") echo "show active" ?>"
             id="people-search" role="tabpanel" aria-labelledby="people-search-tab"
             tabindex="1">
            <form method="post" action="index.php">
                <div class="input-group mb-3">
                    <label for="name" class="input-group-text">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="" name="name">

                    <label for="worked-in" class="input-group-text">Worked in</label>
                    <input type="text" class="form-control" placeholder="" name="worked-in" id="worked-in">

                    <label for="role" class="input-group-text">Role</label>
                    <input type="text" class="form-control" placeholder="" name="role" id="role">

                    <label for="award" class="input-group-text">Award</label>
                    <input type="text" class="form-control" placeholder="" name="award" id="award">

                    <button class="btn btn-outline-secondary" type="submit" name="people-submitted"
                            formaction="?tab=people">Search people
                    </button>
                </div>
            </form>
        </div>
        <div class="tab-pane fade <?php if (isset($_GET["tab"]) && $_GET["tab"] == "likes") echo "show active" ?>"
             id="likes-search" role="tabpanel" aria-labelledby="likes-search-tab"
             tabindex="1">
            <form method="post" action="index.php">
                <div class="input-group mb-3">
                    <span class="input-group-text">Email</span>
                    <input type="text" class="form-control" placeholder="" id="email" name="email" oninput="setEmailFields(this.value)">

                    <button class="btn btn-outline-secondary" type="submit" name="likes-submitted"
                            formaction="?tab=likes">Your likes
                    </button>
                </div>
            </form>
            <form method="post" action="index.php"
                  <div class="input-group mb-3">
                      <span class="input-group-text">Motion picture</span>
                      <input type="text" class="form-control" placeholder="Title" name="motion-picture">

                      <button class="btn btn-outline-secondary" type="submit" name="toggle-like"
                              formaction="?tab=likes">Like motion picture
                      </button>
                  </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <?php
    function dbg($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    // TODO: DEBUGGING, REMOVE LATER
    dbg($_POST);

    // SQL CONNECTIONS
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "COSI127b";

    // Create ORM connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    } catch (PDOException $err) {
        echo "error creating pdo " . $err->getMessage();
        return;
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    class QueryBuilder
    {
        private array $select = [];
        private string $from = '';
        private array $joins = [];
        private array $where = [];
        private string $groupBy = '';
        private array $groups = [];
        public array $params = [];
        private PDO $conn;

        public function __construct(PDO $conn)
        {
            $this->conn = $conn;
        }

        public function reset(): void
        {
            $this->select = [];
            $this->from = '';
            $this->joins = [];
            $this->where = [];
            $this->groupBy = '';
            $this->groups = [];
            $this->params = [];
        }

        public function select(...$columns): self
        {
            $this->select = $columns;
            return $this;
        }

        public function from(string $table): self
        {
            $this->from = $table;
            return $this;
        }

        public function leftJoin(string $table, string $condition): self
        {
            $this->joins[] = " LEFT JOIN $table ON $condition ";
            return $this;
        }

        public function rightJoin(string $table, string $condition): self
        {
            $this->joins[] = " RIGHT JOIN $table ON $condition ";
            return $this;
        }

        public function innerJoin(string $table, string $condition): self
        {
            $this->joins[] = " INNER JOIN $table ON $condition ";
            return $this;
        }

        public function outerJoin(string $table, string $condition): self
        {
            $this->joins[] = " OUTER JOIN $table ON $condition ";
            return $this;
        }

        public function where(string $condition): self
        {
            $this->where[] = $condition;
            return $this;
        }

        public function groupBy(string $column): self
        {
            $this->groupBy = $column;
            return $this;
        }

        public function group(string $column, string $alias): self
        {
            $this->groups[$column] = $alias;
            return $this;
        }

        public function build(): PDOStatement
        {
            $shouldGroup = count($this->groups) > 0 && !empty($this->groupBy);
            $groupColumns = $shouldGroup ? ', ' . implode(', ', array_map(function($column, $alias) {
                return "GROUP_CONCAT(DISTINCT $column SEPARATOR ', ') as $alias";
            }, array_keys($this->groups), array_values($this->groups))) : '';

            $sql = 'SELECT ' . implode(', ', $this->select)
                . $groupColumns
                . ' FROM ' . $this->from
                . implode(' ', $this->joins);

            // append all `WHERE`s joined by `AND`
            if (count($this->where) > 0) {
                $sql .= ' WHERE ' . implode(' AND ', $this->where);
            }

            // handle the group by specifier
            if (!empty($this->groupBy)) {
                $sql .= ' GROUP BY ' . $this->groupBy;
            }

            $sql .= ';';

            $query = $this->conn->prepare($sql);
            dbg($this->params);
            foreach ($this->params as $param => $value) {
                if (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                } else {
                    $type = PDO::PARAM_STR;
                }
                // can't pass $value directly because bindParam requires a reference, so we pass $this->params[$param]
                $query->bindParam($param, $this->params[$param], $type);
            }

            return $query;
        }
    }

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
                            <th class='col-md-2'>Genre</th>
                        </tr>";
    }
    else if (isset($_POST['people-submitted'])) {
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
    }
    else if (isset($_POST['like-submitted'])){}
    else {
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
<script>
    setEmailFields(localStorage.getItem("email"));

    function setEmailFields(value) {
        if (document.getElementById("email").value !== value)
            document.getElementById("email").value = value;
        localStorage.setItem("email", value);
    }

    function setTab(value) {
        history.pushState({}, "", window.location.pathname + "?tab=" + value)
    }

    // stop form from refreshing page and losing state
    document.getElementsByName("form").forEach(function (form) {
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

</body>
</html>