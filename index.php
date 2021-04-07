  <?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Database connection input
  $conn = new PDO('mysql:host=localhost;dbname=zoo;port=8889;charset=UTF8;', "ZooKeeper", "zoo");

  // Set search inputs
  $searchString = "";
  if (isset($_POST['search'])) {
    $searchString = $_POST['search'];
  }

  $searchCategory = "";
  if (isset($_POST['category'])) {
    $searchCategory = $_POST['category'];
  }

  // Search query
  $query = "SELECT * FROM animals WHERE name LIKE '%$searchString%'";

  if ($searchCategory !== "") {
    $query .= " AND category='$searchCategory'";
  }

  // Execute query
  $animals = $conn->query($query);

  $categoryQuery = "SELECT category FROM animals GROUP BY category";

  $categories = $conn->query($categoryQuery);

  ?>

  <html>

  <head>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;900&display=swap" rel="stylesheet">
    <title>The Zoo</title>
  </head>

  <body>
    <header>
      <img src="elefant2.jpg" alt="elephant">
    </header>
    <main>
      <h1>Welcome to The Zoo!</h1>
      <p>Set in one of the most beautiful wildlife environments in the world, The Zoo is home to over 2,800 animals from over 300 species of mammals, birds and reptiles that roam freely in open enclosures resembling their natural habitats, separated from visitors only by moats and wooden fencing. The park also boasts the world's first free-ranging orangutan habitat in a zoo! Spend a day exploring this award winning zoo - a great family activity in Singapore. Book your The Zoo ticket with great discount today!</p>


      <div class="searchbox">
        <h2>Search animals</h2>
        <form method="post" action="index.php">
          <input type="text" name="search" placeholder="Search animal..." />
          <input type="submit" value="Search" />
          <select name="category">
            <option value="">All</option>
            <?php
            foreach ($categories as $category) {
              echo "<option value='" . $category['category'] . "'>" . $category['category'] . "</option>";
            }
            ?>
          </select>
        </form>
      </div>

      <h3>Search results</h3>
      <div class="results">
        <ul>
          <?php
          foreach ($animals as $animal) {
            echo "<li>" . $animal['name'] . "</li>";
          }
          ?>
        </ul>
      </div>

      <div class="uploadbox">
        <form enctype="multipart/form-data" action="index.php" method="POST">
          <!-- MAX_FILE_SIZE must precede the file input field -->
          <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
          <!-- Name of input element determines name in $_FILES array -->
          <h3> Upload File</h3>
          <p>Upload a photo of your favourite animal to have a chance to win a trip to The Zoo for two people. The winner gets two nights all inclusive at the Zoo, all expenses paid for. </p>
          <input type="file" name="fileToUpload" id="ftu" />
          <input type="submit" value="Send File" />

          <?php
          //UPLOAD FILES
          if ($_FILES) {

            $uploadDir = "uploads/";
            $uploadPath = $uploadDir . basename($_FILES['fileToUpload']['name']);

            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadPath)) {
              echo "File is valid, and was successfully uploaded";
            } else {
              echo "Invaild file, please try again";
            }
          }
          ?>
        </form>
      </div>
    </main>
    <footer>
      <p>© The Zoo 2021</p>
    </footer>
  </body>



  </html>