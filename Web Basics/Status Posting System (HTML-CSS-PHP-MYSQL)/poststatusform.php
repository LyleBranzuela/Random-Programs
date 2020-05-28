<html lang="en">

<head>
    <title>Post Status Form</title>
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

    <!-- Status Posting System Container -->
    <div class="container-fluid col-sm-10" id="custom-margin-top">
        <div class="card">
            <div class="card-header subThemeColor3 text-center">
                <h3>Status Posting System</h3>
            </div>
            <div class="card-body subThemeColor3" style="padding-bottom: 0px;">
                <!-- Form -->
                <form class="form-horizontal" method="POST" action="./poststatusprocess.php" style="margin-bottom: 0px;">
                    <!-- Form Group for Status Code -->
                    <div class=" form-group row">
                        <label class="control-label col-sm-3 text-center" for="statusCode">Status Code <strong class="errorColor">*</strong></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="statusCode" placeholder="Enter Code" pattern="^[S]\d{4}$" maxlength="5" required />
                            <small class="form-text text-muted">
                                The code must start with an uppercase letter "S" followed by 4 numbers. (Ex. S0001)
                            </small>
                        </div>
                    </div>
                    <!-- Form Group for Status -->
                    <div class="form-group row">
                        <label class="control-label col-sm-3 text-center" for="statusText">Status <strong class="errorColor">*</strong></label>
                        <div class="col-sm-9">
                            <textarea type="text" class="form-control" name="statusText" placeholder="Enter Status" rows=5 required></textarea>
                            <small class="form-text text-muted">
                                Allowed Characters: Spaces( ), Comma (,), Period (.), Exclamation Mark (!), and Question Mark (?).
                            </small>
                        </div>
                    </div>
                    <!-- Form Group for Share Radio Buttons -->
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-center">Share</label>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shareSetting" id="publicRadio" value="Public">
                                <label class="form-check-label" for="publicRadio">
                                    Public
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shareSetting" id="friendsRadio" value="Friends">
                                <label class="form-check-label" for="friendsRadio">
                                    Friends
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shareSetting" id="onlyMeRadio" value="Only Me">
                                <label class="form-check-label" for="onlyMeRadio">
                                    Only Me
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Form Group for Date -->
                    <div class="form-group row">
                        <label class="control-label col-sm-3 text-center" for="date">Date</label>
                        <div class="col-sm-9">
                            <!-- Input's value only takes dates in the format ("Y-m-d") but shows as d/m/Y in the input HTML side --> 
                            <input type="date" class="form-control" name="datePosted" placeholder="Date" value="<?php echo date("Y-m-d") ?>" required>
                        </div>
                    </div>
                    <!-- Form Group for Permission Type -->
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-center">Permission Type</label>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permType[]" id="likeBox" value="Allow Likes">
                                <label class="form-check-label" for="likeBox">
                                    Allow Like
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permType[]" id="commentBox" value="Allow Comments">
                                <label class="form-check-label" for="commentBox">
                                    Allow Comment
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permType[]" id="shareBox" value="Allow Shares">
                                <label class="form-check-label" for="shareBox">
                                    Allow Share
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Status Form Buttons (Post and Reset) -->
                    <div class="row float-middle">
                        <input type="submit" class="btn btn-customMain col-sm-6" value="Post">
                        <input type="reset" class="btn btn-customMain col-sm-6">
                    </div>
                </form>
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