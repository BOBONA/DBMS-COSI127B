# IMBD Clone

## Contribution

To add a query,

1. Add a tab to the tab group
    ```php
    <ul class="nav nav-tabs" role="tablist">
        <!-- other tabs ... -->
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "<tab-name>") echo "active" ?>"
                    id="<tab-name>-tab" data-bs-toggle="tab"
                    data-bs-target="#<tab-name>-search" type="button" role="tab"
                    aria-controls="<tab-name>-search" onclick="setTab('<tab-name>')">Tab Name
            </button>
        </li>
    </ul>
    ```
1. Add a new tab content
    ```php
    <div class="tab-content">
        <div class="tab-pane fade <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "<tab-name>") echo "show active" ?>"
             id="<tab-name>-search" role="tabpanel"
             aria-labelledby="<tab-name>-search-tab" tabindex="0">
            <form method="post" action="index.php">
                <!-- ... -->
            </form>
        </div>
    </div>
    ```
1. Add items to the form
    ```php
   <form method="post" action="index.php">
       <div class="input-group mb-3">
           <label for="name" class="input-group-text">Name</label>
           <input type="text" class="form-control" id="name" placeholder="" name="name">
   
           <!-- more fields -->
   
           <input type="hidden" id="motion-picture-search-email" value="" name="email">
           <button class="btn btn-outline-secondary" type="submit" name="<tab-name>-submitted">Search
        </div>
    </form>
    ```
1. Add a block to the if statement
    ```php
   // ...
    else if (isset($_POST["<tab-name>-submitted"])) {
        // ...
    }
    ```
   
1. Add the query and `$table_header` to the block
   ```php
        $qb = $qb->select('M.name', 'M.rating', 'M.production', 'M.budget', "G.genre_name")
            ->from('MotionPicture M')
            ->leftJoin('Genre G', 'M.id = G.mpid');

        if (!empty($_POST['title'])) {
            $qb->where("M.name LIKE :title");
            $qb->params[':title'] = "%" . $_POST['title'] . "%";
        }
        if (!empty(/* more attrs */)) {
            // ...
        }
         
        $table_header = "<tr>
                            <th class='col-md-2'>Name</th>
                            <th class='col-md-2'>Rating</th>
                            <th class='col-md-2'>Production</th>
                            <th class='col-md-2'>Budget</th>
                            <th class='col-md-2'>Genre</th>
                        </tr>";
   ```

