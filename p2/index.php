<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSI 127b</title>
</head>

<body>
    <div class="container">
        <h1 style="text-align:center">COSI 127b</h1><br>
        <h3 style="text-align:center">Connecting Front-End to MySQL DB</h3><br>
    </div>

    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="motion-picture-tab" data-bs-toggle="tab" data-bs-target="#motion-picture-search" type="button" role="tab" aria-controls="motion-picture-search" aria-selected="true">Motion Pictures</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="actor-tab" data-bs-toggle="tab" data-bs-target="#actor-search" type="button" role="tab" aria-controls="actor-search" aria-selected="false">Actors</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="motion-picture-search" role="tabpanel" aria-labelledby="motion-picture-search-tab" tabindex="0">
                <form id="ageLimitForm" method="post" action="index.php">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Title" name="title">
                        <input type="text" class="form-control" placeholder="Genre" name="genre">
                        
                        <button class="btn btn-outline-secondary" type="submit" name="submitted">Search</button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="actor-search" role="tabpanel" aria-labelledby="actor-search-tab" tabindex="0">
                
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Guests</h1>
        <?php
        // we want to check if the submit button has been clicked (in our case, it is named Query)
        if (isset($_POST['submitted'])) {
            // set age limit to whatever input we get
            // ideally, we should do more validation to check for numbers, etc. 
            $ageLimit = $_POST["inputAge"];
        } else {
            // if the button was not clicked, we can simply set age limit to 0 
            // in this case, we will return everything
            $ageLimit = 0;
        }

        // we will now create a table from PHP side 
        echo "<table class='table table-md table-bordered'>";
        echo "<thead class='thead-dark' style='text-align: center'>";

        // initialize table headers
        // YOU WILL NEED TO CHANGE THIS DEPENDING ON TABLE YOU QUERY OR THE COLUMNS YOU RETURN
        echo "<tr><th class='col-md-2'>Firstname</th><th class='col-md-2'>Lastname</th></tr></thead>";

        // generic table builder. It will automatically build table data rows irrespective of result
        class TableRows extends RecursiveIteratorIterator
        {
            function __construct($it)
            {
                parent::__construct($it, self::LEAVES_ONLY);
            }

            function current()
            {
                return "<td style='text-align:center'>" . parent::current() . "</td>";
            }

            function beginChildren()
            {
                echo "<tr>";
            }

            function endChildren()
            {
                echo "</tr>" . "\n";
            }
        }

        // SQL CONNECTIONS
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "COSI127b";

        try {
            // We will use PDO to connect to MySQL DB. This part need not be 
            // replicated if we are having multiple queries. 
            // initialize connection and set attributes for errors/exceptions
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare statement for executions. This part needs to change for every query
            $stmt = $conn->prepare("SELECT first_name, last_name FROM guests where age>=$ageLimit");

            // execute statement
            $stmt->execute();

            // set the resulting array to associative. 
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            // for each row that we fetched, use the iterator to build a table row on front-end
            foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                echo $v;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        echo "</table>";
        // destroy our connection
        $conn = null;

        ?>

    </div>
</body>

</html>