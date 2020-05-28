<html lang="en">

<head>
    <title>Post Status Process</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css" />
    <!-- Latest compiled and minified CSS of Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-dark navbar-expand-lg mainThemeColor fixed-top">
        <a class="navbar-brand mainThemeColor">SPS</a>
        <!-- Hamburger Button for when the screen is too small -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.html" alt="Home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html" alt="About">About</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="poststatusform.php" alt="Post Status">Post Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="searchstatusform.html" alt="Search Status">Search Status</a>
                </li>
            </div>
        </div>
    </nav>

    <!-- PHP Code to process information in the Form -->
    <?php

    require_once('../../conf/assignsqlinfo.inc.php');
    // mysqli_connect returns false if connection failed, otherwise a connection value
    $conn = @mysqli_connect(
        $sql_host,
        $sql_user,
        $sql_pass,
        $sql_db
    );

    // Initialising the Validation / Confirmation Variables
    $databaseHasConn = $databaseInsertValid = false;
    $statusCodeFormatValid = $statusCodeUniqueValid = false; // Status Code Flags
    $statusTextFormatValid = false; // Status Text Flags

    // Function to check if the designated pattern is right
    function patternCheck($pattern, $data)
    {
        // Only return data if it matches, if it doesn't then dont return anything
        if (preg_match($pattern, $data)) {
            return $data;
        }
    }

    // Function to handle all the status flags validity
    function statusFlagHandler($statusValid)
    {
        // Status Code Flags
        $statusSuccess = "list-group-item-success";
        $statusError = "list-group-item-danger";
        $statusDefault = "list-group-item-light";

        // If it's not a typical Status Flag
        if ($statusValid === "default") {
            return $statusDefault;
        }

        // Status Code Returns
        if ($statusValid) {
            return $statusSuccess;
        } else if (!$statusValid) {
            return $statusError;
        }
    }


    // Setting up the Variables
    $statusCode = $statusText = $datePosted = $shareSetting = $dbDate = $unCheckedStatusText = $permType = "";
    $permTypeArr = [];
    // Checks if connection to the status database is successful
    if (!$conn) {
        $databaseHasConn = false;
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $databaseHasConn = true;

            // Check if they are set properly
            if (isset($_POST["statusCode"]) && isset($_POST["statusText"]) && isset($_POST["datePosted"])) {
                // Pattern Checks 
                $statusCode = patternCheck("/^[S]\d{4}$/", $_POST["statusCode"]); // Pattern: Uppercase S and 4 Digits - ex.(S0001)
                $statusText = patternCheck("/^[\w\s\,\.\!\?]+$/", $_POST["statusText"]); // Pattern: Any Alphanumeric Characters and Other specified Characters
                $unCheckedStatusText = $_POST["statusText"]; // Unchecked String to be displayed so the user can look back into it
                $datePosted = date("d/m/Y", strtotime($_POST["datePosted"])); // Reformat to Pattern: dd/mm/yyyy to show in the HTML Side
                $dbDate = $_POST["datePosted"]; // Variable to be put in Database Side (in Y-m-d format for the sql date datatype)

                // Update Format Validation Flags - Being empty means it failed the pattern check
                $statusCodeFormatValid = (!empty($statusCode));
                $statusTextFormatValid = (!empty($statusText));

                // Check if the Status Code is Unique
                $checkQuery = "SELECT COUNT(*) AS duplicates FROM $sql_tble WHERE statusCode = \"$statusCode\"";
                $countResult = mysqli_query($conn, $checkQuery);
                $duplicateAmount = mysqli_fetch_assoc($countResult);
                $statusCodeUniqueValid = ($duplicateAmount['duplicates'] == 0); // Update Status Code Uniqueness Flag

                // Check if the share radio buttons are set (Optional)
                if (isset($_POST["shareSetting"])) {
                    $shareSetting = $_POST["shareSetting"];
                }

                // Check if the perm checkbox buttons are set (Optional)
                if (isset($_POST["permType"])) {
                    // eg. "Allow Like, Allow Comment, Allow Share"
                    $permType = implode(', ', $_POST["permType"]);
                    $permTypeArr = $_POST["permType"];
                }

                // Set up the SQL command to add the data into the table
                $query = "insert into $sql_tble"
                    . "(statusCode, statusText, datePosted, shareSetting, permType)"
                    . "values"
                    . "('$statusCode','$statusText','$dbDate', '$shareSetting', '$permType')";

                // Executes the query
                $result = mysqli_query($conn, $query);
                // Checks if the execution was successful
                if (!$result) {
                    $databaseInsertValid = false;
                } else {
                    $databaseInsertValid = true;
                } // If successful query operation
            }
        }
        // Close the database connection
        mysqli_close($conn);
    }
    ?>

    <!-- Confirmation / Error Area -->
    <div class="container" id="custom-margin-top">
        <div class="subThemeColor text-center pb-1 pt-1">
            <h3>Post Status Checks</h3>
        </div>
        <div class="card">
            <div id="accordion">
                <!-- Database Check -->
                <div class="card">
                    <div class="card-header" id="headingDB">
                        <h5 class="mb-0">
                            <button class="btn btn-customMain" data-toggle="collapse" data-target="#collapseDB" aria-expanded="true" aria-controls="collapseDB">
                                Database Issues -
                                <!-- Counting the Errors Found in the Database Check -->
                                <span class="badge badge-danger"><?php echo (int) (!$databaseHasConn + !$databaseInsertValid) ?></span>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseDB" class="collapse show" aria-labelledby="headingDB" data-parent="#accordion">
                        <div class="card-body">
                            <!-- Database Check Tabs (Database Connection Check, Database Connection Check) -->
                            <div class="row">
                                <div class="col-6 text-center">
                                    <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                        <a class="list-group-item list-group-item-action active <?php echo statusFlagHandler($databaseHasConn) ?>" id="list-db-conn-list" data-toggle="list" href="#list-db-conn" role="tab" aria-controls="db-conn">Database Connection</a>
                                        <a class="list-group-item list-group-item-action <?php echo statusFlagHandler($databaseInsertValid) ?>" id="list-db-insert-list" data-toggle="list" href="#list-db-insert" role="tab" aria-controls="db-insert">Data Insertion</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Database Check Tab's Descriptions -->
                            <div class="row">
                                <div class="col-12">
                                    <hr />
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="list-db-conn" role="tabpanel" aria-labelledby="list-db-conn-list">
                                            The website must be connected to the database. The database
                                            <strong><?php echo (($databaseHasConn) ? " is" : " is not") ?></strong>
                                            currently connected to the database.
                                        </div>
                                        <div class="tab-pane fade" id="list-db-insert" role="tabpanel" aria-labelledby="list-db-insert-list">
                                            The website must insert data to the database with no issues. The database
                                            <strong><?php echo (($databaseInsertValid) ? " has no" : " has") ?></strong>
                                            issues with the insertion of data. If the database is successfully connected, then refer to the checks below.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Status Code Check -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-customMain" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Status Code Check -
                                    <!-- Counting the Errors Found in the Status Code Check -->
                                    <span class="badge badge-danger"><?php echo (int) (!$statusCodeFormatValid + !$statusCodeUniqueValid) ?></span>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <!-- Status Code Tabs (Status Code, Format, Unique) -->
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                            <a class="list-group-item list-group-item-action list-group-item-dark active" id="list-code-list" data-toggle="list" href="#list-code" role="tab" aria-controls="code">Status Code</a>
                                            <a class="list-group-item list-group-item-action <?php echo statusFlagHandler($statusCodeFormatValid) ?>" id="list-formatting-list" data-toggle="list" href="#list-formatting" role="tab" aria-controls="formatting">Formatting</a>
                                            <a class="list-group-item list-group-item-action <?php echo statusFlagHandler($statusCodeUniqueValid) ?>" id="list-unique-list" data-toggle="list" href="#list-unique" role="tab" aria-controls="unique">Unique</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Status Code Tab's Descriptions -->
                                <div class="row">
                                    <div class="col-12">
                                        <hr />
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="list-code" role="tabpanel" aria-labelledby="list-code-list"><strong>Status Code: </strong> <?php echo $statusCode ?></div>
                                            <div class="tab-pane fade" id="list-formatting" role="tabpanel" aria-labelledby="list-formatting-list">
                                                The status code must fit the specified format. The status code
                                                <strong><?php echo "\"" . $statusCode . "\"" . (($statusCodeFormatValid) ? " follows" : " does not follow") ?></strong>
                                                the format. The status code must start with an uppercase letter "S" followed by 4 numbers. (Ex. S0001)
                                            </div>
                                            <div class="tab-pane fade" id="list-unique" role="tabpanel" aria-labelledby="list-unique-list">
                                                The status code must be unique (The status code must not exist within the database). The status code
                                                <strong><?php echo "\"" . $statusCode . "\"" . (($statusCodeUniqueValid) ? " does not exist" : " already exists.") ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Status Text Check -->
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-customMain collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Status Text Check -
                                        <!-- Counting the Errors Found in the Status Text Check -->
                                        <span class="badge badge-danger"><?php echo (int) (!$statusTextFormatValid) ?></span>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <!-- Status Text Tabs (Status Text, Format) -->
                                    <div class="row">
                                        <div class="col-6 text-center">
                                            <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                                <a class="list-group-item list-group-item-action list-group-item-dark active" id="list-statusText-list" data-toggle="list" href="#list-statusText" role="tab" aria-controls="statusText">Status Text</a>
                                                <a class="list-group-item list-group-item-action <?php echo statusFlagHandler($statusTextFormatValid) ?>" id="list-text-format-list" data-toggle="list" href="#list-text-format" role="tab" aria-controls="text-format">Formatting</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status Text Tab's Descriptions -->
                                    <div class="row">
                                        <div class="col-12">
                                            <hr />
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="list-statusText" role="tabpanel" aria-labelledby="list-statusText-list"><strong>Status Text: </strong> <?php echo $unCheckedStatusText ?></div>
                                                <div class="tab-pane fade" id="list-text-format" role="tabpanel" aria-labelledby="list-text-format-list">
                                                    <p>The status text inputted <strong><?php echo (($statusTextFormatValid) ? "does not have" : "has") ?></strong> illegal characters.</p>
                                                    <p><strong>Allowed Characters: </strong> Spaces( ), Comma (,), Period (.), Exclamation Mark (!), and Question Mark (?)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Status Share Setting Check -->
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-customMain collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Status Setting Check
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <!-- Status Share Setting Tabs (Public, Friends, Only Me) -->
                                    <div class="row">
                                        <div class="col-6 text-center">
                                            <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                                <a class="list-group-item list-group-item-action <?php echo (($shareSetting == "Public") ? statusFlagHandler(true) . " active" : statusFlagHandler("default")) ?>" id="list-public-list" data-toggle="list" href="#list-public" role="tab" aria-controls="public">Public</a>
                                                <a class="list-group-item list-group-item-action <?php echo (($shareSetting == "Friends") ? statusFlagHandler(true) . " active" : statusFlagHandler("default")) ?>" id="list-friends-list" data-toggle="list" href="#list-friends" role="tab" aria-controls="friends">Friends</a>
                                                <a class="list-group-item list-group-item-action <?php echo (($shareSetting == "Only Me") ? statusFlagHandler(true) . " active" : statusFlagHandler("default")) ?>" id="list-only-me-list" data-toggle="list" href="#list-only-me" role="tab" aria-controls="only-me">Only Me</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status Share Setting Tab's Descriptions -->
                                    <div class="row">
                                        <div class="col-12">
                                            <hr />
                                            <div class="tab-content" id="nav-tabContent">
                                                <!-- The Active Share Setting is shown as the default -->
                                                <div class="tab-pane fade <?php echo (($shareSetting == "Public") ? "show active" : "") ?>" id="list-public" role="tabpanel" aria-labelledby="list-public-list">
                                                    <strong><?php echo (($shareSetting == "Public") ? "Chosen Setting." : "") ?></strong> The status can be seen by any users.
                                                </div>
                                                <div class="tab-pane fade <?php echo (($shareSetting == "Friends") ? "show active" : "") ?>" id="list-friends" role="tabpanel" aria-labelledby="list-friends-list">
                                                    <strong><?php echo (($shareSetting == "Friends") ? "Chosen Setting." : "") ?></strong> The status can only be seen to user's friends and the user.
                                                </div>
                                                <div class="tab-pane fade <?php echo (($shareSetting == "Only Me") ? "show active" : "") ?>" id="list-only-me" role="tabpanel" aria-labelledby="list-only-me-list">
                                                    <strong><?php echo (($shareSetting == "Only Me") ? "Chosen Setting." : "") ?></strong> The status can only be seen to the user.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Date Check -->
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <button class="btn btn-customMain collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Date
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                <!-- Date Tabs (Date) -->
                                <div class="row">
                                    <div class="col-2 text-center">
                                        <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                            <a class="list-group-item list-group-item-action list-group-item-dark active" id="list-allow-date" data-toggle="list" href="#list-date" role="tab" aria-controls="date"><?php echo (empty($datePosted)) ? "Set Date" : $datePosted ?></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date Tab's Descriptions -->
                                <div class="row">
                                    <div class="col-12">
                                        <hr />
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="list-date" role="tabpanel" aria-labelledby="list-date-list">
                                                The Date set by the user.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Permission Type Check -->
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                                <button class="btn btn-customMain collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Permission Types -
                                    <!-- The amount of permissions the user has set -->
                                    <span class="badge badge-info"><?php echo ((!empty($permType)) ? sizeof(explode(', ', $permType)) : 0) ?></span>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                                <!-- Permission Type Tabs (Allow Like, Allow Comments, Allow Share) -->
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="list-group list-group-horizontal-sm" id="list-tab" role="tablist">
                                            <a class="list-group-item list-group-item-action <?php echo ((in_array("Allow Likes", $permTypeArr)) ? statusFlagHandler(true) : statusFlagHandler("default")) ?> active" id="list-allow-likes-list" data-toggle="list" href="#list-allow-likes" role="tab" aria-controls="allow-likes">Allow Like</a>
                                            <a class="list-group-item list-group-item-action <?php echo ((in_array("Allow Comments", $permTypeArr))  ? statusFlagHandler(true) : statusFlagHandler("default")) ?>" id="list-allow-comments-list" data-toggle="list" href="#list-allow-comments" role="tab" aria-controls="allow-comments">Allow Comment</a>
                                            <a class="list-group-item list-group-item-action <?php echo ((in_array("Allow Shares", $permTypeArr)) ? statusFlagHandler(true) : statusFlagHandler("default")) ?>" id="list-allow-shares-list" data-toggle="list" href="#list-allow-shares" role="tab" aria-controls="allow-shares">Allow Share</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Permission Type Tab's Descriptions -->
                                <div class="row">
                                    <div class="col-12">
                                        <hr />
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="list-allow-likes" role="tabpanel" aria-labelledby="list-allow-likes-list">
                                                <strong><?php echo ((in_array("Allow Likes", $permTypeArr)) ? "Chosen Setting." : "") ?></strong> Other users can like the status.
                                            </div>
                                            <div class="tab-pane fade" id="list-allow-comments" role="tabpanel" aria-labelledby="list-allow-comments-list">
                                                <strong><?php echo ((in_array("Allow Comments", $permTypeArr)) ? "Chosen Setting." : "") ?></strong> Other users can comment on the status.
                                            </div>
                                            <div class="tab-pane fade" id="list-allow-shares" role="tabpanel" aria-labelledby="list-allow-shares-list">
                                                <strong><?php echo ((in_array("Allow Shares", $permTypeArr)) ? "Chosen Setting." : "") ?></strong> Other users can share the status.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <button class="btn btn-customMain col-sm-6" onclick="history.back()">Go Back</button>
                                <a class="btn btn-customMain col-sm-6 <?php echo (($databaseInsertValid) ? "" : "disabled") ?>" role="button" href="./index.html">Confirm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer mainThemeColor fixed-bottom "></footer>


    <!-- Bootstrap JS, Popper.js, and jQuery Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>