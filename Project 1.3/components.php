<?php
function TextInput(string $name, string $label, $value = null): string
{
    $value = $value ?? ($_POST[$name] ?? '');
    return <<<HTML
    <label for="$name" class="input-group-text">$label</label>
    <input type="text" class="form-control" id="$name" name="$name" value="$value">
    HTML;
}


/**
 * @param string $name
 * @param array $children An array of strings
 * @return string
 */
function Form(string $name, array $children): string
{
    $childrenHtml = implode('', $children);
    return <<<HTML
    <form method="post" action="index.php">
    <div class="input-group mb-3">
    $childrenHtml
    <button class="btn btn-outline-secondary" type="submit" name="$name-submitted" formaction="?tab=$name">Search $name</button>
    </div>
    </form>
    HTML;

}


/* drop down menu

//                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
//                            aria-expanded="false">Type
//                    </button>
//                    <ul class="dropdown-menu">
//                        <li class="dropdown-item form-check">
//                            <input class="form-check-input mx-1" type="checkbox" id="flexCheckDefault"
//                                   name="search-movies" <?php if (isset($_POST['search-movies'])) echo "checked" ?><!-->-->
<!--                            <label class="form-check-label" for="flexCheckDefault">Movie</label>-->
<!--                        </li>-->
<!--                        <li class="dropdown-item form-check">-->
<!--                            <input class="form-check-input mx-1" type="checkbox" id="flexCheckDefault"-->
<!--                                   name="search-tv-shows" --><?php //if (isset($_POST['search-tv-shows'])) echo "checked" ?><!-->-->
<!--                            <label class="form-check-label" for="flexCheckDefault">TV Show</label>-->
<!--                        </li>-->
<!--                    </ul>-->*/

function DropDown(string $name, string $label, array $options, $value = null): string
{
    $value = $value ?? ($_POST[$name] ?? '');

    $optionsHtml = '';
    foreach ($options as $option) {

        /*
        <li class="dropdown-item form-check">
            <input class="form-check-input mx-1" type="checkbox" id="flexCheckDefault"
                  name="search-movies" <?php if (isset($_POST['search-movies'])) echo "checked" ?>
            <label class="form-check-label" for="flexCheckDefault">Movie</label>
        </li>
        */
        $checked = in_array($option, (array)$value) ? 'checked' : '';
        $optionsHtml .= <<<HTML
        <li class="dropdown-item form-check">
            <input class="form-check-input mx-1" type="checkbox" id="flexCheckDefault"
                  name="$name" value="$option" $checked>
            <label class="form-check-label" for="flexCheckDefault">$option</label>
        </li>
        HTML;

    }

    return <<<HTML
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">$label
    </button>
    <ul class="dropdown-menu">
        $optionsHtml
    </ul>
    HTML;
}

function Tab(string $name, string $label): string
{
    $active = (!isset($_GET["tab"]) || $_GET["tab"] == $name) ? 'active' : '';
    return <<<HTML
    <li class="nav-item" role="presentation">
        <button class="nav-link $active" id="$name-tab" data-bs-toggle="tab"
                data-bs-target="#$name-search" type="button" role="tab"
                aria-controls="$name-search" onclick="setTab('$name')">$label
        </button>
    </li>
    HTML;
}

function Tabs(array $tabs): string
{
    $tabsHtml = '';
    foreach ($tabs as $name => $label) {
        $tabsHtml .= Tab($name, $label);
    }
    return <<<HTML
    <ul class="nav nav-tabs" role="tablist">
        $tabsHtml
    </ul>
    HTML;
}

?>

