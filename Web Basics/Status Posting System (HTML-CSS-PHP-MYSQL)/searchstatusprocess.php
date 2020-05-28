<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search Status Process</title>
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
                <li class="nav-item ">
                    <a class="nav-link" href="poststatusform.php" alt="Post Status">Post Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="searchstatusform.html" alt="Search Status">Search Status</a>
                </li>
            </div>
        </div>
    </nav>

    <div class="container-fluid" id="custom-margin-top">
        <div class="subThemeColor text-center pb-2 pt-2">
            <h3>Status Search Results</h3>
        </div>
        <!-- PHP Code to process search information in the Form -->
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
        $databaseHasConn = $databaseSearchValid = $searchTextValid = false;

        // Checks if connection to the status database is successful
        if (!$conn) {
            $databaseHasConn = false;
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $databaseHasConn = true;
                // Check if the search bar is empty
                if (!empty($_GET["statusText"])) {
                    $searchTextValid = true;

                    $statusText = $_GET["statusText"];
                    // Make and Execute the query for the search
                    $query = "SELECT * FROM $sql_tble WHERE statusText LIKE '%$statusText%'";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        $databaseSearchValid = false;
                    } else {
                        $databaseSearchValid = true;
                        // Count the amount of Searches found in the database
                        $countSearchQuery = "SELECT COUNT(*) AS searches FROM $sql_tble WHERE statusText LIKE '%$statusText%'";
                        $countResult = mysqli_query($conn, $countSearchQuery);
                        $searchAmount = mysqli_fetch_assoc($countResult);


                        // Checks and Validiation
                        echo "<ul class=\"list-group list-group-horizontal text-center\">"
                            . "<li class=\"list-group-item flex-fill border border-dark" . (($databaseHasConn) ? " list-group-item-success" : " list-group-item-danger")  . "\">Database Connection</li>"
                            . "<li class=\"list-group-item flex-fill border border-dark" .  (($databaseSearchValid) ? " list-group-item-success" : " list-group-item-danger") . "\">Search Text Query</li>"
                            . "<li class=\"list-group-item flex-fill border border-dark" . (($searchTextValid) ? " list-group-item-success" : " list-group-item-danger") . "\">Search Text Not Empty</li>"
                            . "</ul>";

                        // Display the retrieved records
                        echo "<table class=\"table table-bordered \">";
                        echo "<caption class=\"ml-2\">" . $searchAmount['searches'] . " search results found.</caption>";
                        echo "<thead class=\"subThemeColor2\">";
                        echo "<tr>\n"
                            . "<th scope=\"col\">#</th>\n"
                            . "<th scope=\"col\">Status Code</th>\n"
                            . "<th scope=\"col\">Status Text</th>\n"
                            . "<th scope=\"col\">Date Posted</th>\n"
                            . "<th scope=\"col\">Share Setting</th>\n"
                            . "<th scope=\"col\">Permission Type</th>\n"
                            . "</tr>\n";
                        echo "</thead>";

                        $rowCount = 1;
                        // Prints if the search amount is empty (For looks only)
                        if ($searchAmount['searches'] == 0) {
                            while ($rowCount <= 15) {
                                $tbodyColor = ($rowCount % 2) ? "subThemeColor4" : "subThemeColor3";
                                echo "<tbody class=\"$tbodyColor\">";
                                echo "<tr>";
                                echo "<td></td>";
                                echo "<td> </td>";
                                echo "<td> </td>";
                                echo "<td> </td>";
                                echo "<td> </td>";
                                echo "<td> </td>";
                                echo "</tr>";
                                echo "</tbody>";
                                $rowCount++;
                            }
                        } else {
                            // Looping through the statusText field using a result pointer
                            while ($row = mysqli_fetch_assoc($result)) {
                                $tbodyColor = ($rowCount % 2) ? "subThemeColor4" : "subThemeColor3";
                                echo "<tbody class=\"$tbodyColor\">";
                                echo "<tr>";
                                echo "<td>", $rowCount++, "</td>";
                                echo "<td>", $row["statusCode"], "</td>";
                                echo "<td>", $row["statusText"], "</td>";
                                echo "<td>", date("d/m/Y", strtotime($row["datePosted"])), "</td>";
                                echo "<td>", $row["shareSetting"], "</td>";
                                echo "<td>", $row["permType"], "</td>";
                                echo "</tr>";
                                echo "</tbody>";
                            }
                        } // If there are results in the search
                        echo "</table>";

                        // Frees up the memory, after using the result pointer
                        mysqli_free_result($result);
                    }
                } else {
                    $searchTextValid = false;
                }
            }
            // Close the database connection
            mysqli_close($conn);

            // Options to go back and search for more and go to homepage
            echo "<button class=\"btn btn-customMain float-left\" onclick=\"history.back()\">Search For Another Status</button>"
                . "<a class=\"btn btn-customMain float-right\" role=\"button\" href=\"./index.html\">Go Home</a>";
        }
        ?>

    </div>

    <!-- Footer -->
    <footer class="footer mainThemeColor fixed-bottom "></footer>


    <!-- Bootstrap JS, Popper.js, and jQuery Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>