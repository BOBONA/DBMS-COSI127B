<?php

/**
 * @param array $tabs an array of HTML strings
 * @return string
 */
function Tabs(array $tabs): string
{
    $tabsHtml = implode('', $tabs);
    return <<<HTML
        <ul class="nav nav-tabs flex-row" role="tablist">
            $tabsHtml
        </ul>
    HTML;
}

/**
 * @param string $name tab name, for metadata and the target
 * @param string $label
 * @param bool $default whether this tab is the default
 * @return string
 */
function Tab(string $name, string $label, bool $default = false): string
{
    $active = ((!isset($_GET["tab"]) && $default) || (isset($_GET["tab"]) && $_GET["tab"] == $name)) ? 'active' : '';
    return <<<HTML
        <li class="nav-item" role="presentation">
            <button class="nav-link $active" id="$name-tab" data-bs-toggle="tab"
                    data-bs-target="#$name-search" type="button" role="tab"
                    aria-controls="$name-search" onclick="setTab('$name')">$label
            </button>
        </li>
    HTML;
}

/**
 * @param string $name tab name for metadata
 * @param array $children an array of HTML strings
 * @param bool $default whether this tab is the default
 * @return string
 */
function TabContent(string $name, array $children, bool $default = false): string
{
    $childrenHtml = implode('', $children);
    $active = ((!isset($_GET["tab"]) && $default) || (isset($_GET["tab"]) && $_GET["tab"] == $name)) ? 'show active' : '';
    return <<<HTML
        <div class="tab-pane fade $active"
             id="$name-search" role="tabpanel"
             aria-labelledby="$name-search-tab">
            $childrenHtml
        </div>
    HTML;
}

/**
 * @param string $name form name, for metadata and the submit button
 * @param string $tab the tab to submit to
 * @param array $children an array of HTML strings
 * @param string|null $buttonText
 * @param bool $inline whether to display the form inline
 * @return string
 */
function Form(string $name, string $tab, array $children, string $buttonText = null, bool $inline = false): string
{
    $childrenHtml = implode('', $children);
    $buttonText = $buttonText ?? "Search $name";
    $inline = $inline ? 'd-inline-block' : '';
    return <<<HTML
        <form method="post" action="index.php" class="$inline">
            <div class="input-group mb-3">
                $childrenHtml
                <button class="btn btn-outline-secondary" type="submit" name="$name-submitted" formaction="?tab=$tab">$buttonText</button>
            </div>
        </form>
    HTML;
}

/**
 * @param string $name input name, for metadata
 * @param string $label
 * @param array $options
 * @return string
 */
function DropDown(string $name, string $label, array $options): string
{
    $value = $_POST[$name] ?? '';

    $optionsHtml = '';
    foreach ($options as $option) {
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

/**
 * @param string $name input name, for metadata
 * @param string $label
 * @return string
 */
function TextInput(string $name, string $label): string
{
    $value = $_POST[$name] ?? '';
    return <<<HTML
        <label for="$name" class="input-group-text">$label</label>
        <input type="text" class="form-control" id="$name" name="$name" value="$value">
    HTML;
}

/**
 * @param array $columns column names
 * @return string
 */
function TableHeader(array $columns): string
{
    $columnsHtml = '';
    foreach ($columns as $column) {
        $columnsHtml .= "<th class='col-md-2'>$column</th>";
    }
    return "<tr>$columnsHtml</tr>";
}