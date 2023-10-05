<!DOCTYPE html>
<html>
    <head>
        <title>Team 25 Webpage</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <header>
        <h1>Driver Incentive Service Webpage</h1>
        <form method="post">
            <input type="submit" value="Display About Page" name="button1" class="button">
            <input type="submit" value="Add New Sprint" name="button2" class="button">
            <input type="submit" value="Edit Sprint" name="button3" class="button">
            <br>
            <br>
    </header>
    <body>
        <?php
            $message = "";

            $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
            $username = "admin";
            $password = "performancepineapple25";
            $dbname = "team_25_database";

            if(isset($_POST['button1'])) 
            {
                // Create database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check database connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query the database
                $sql = "SELECT * FROM about ORDER BY version DESC";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $sprNum = $row["version"];
                $teamNum = $row["team_num"];
                $releaseDate = $row["release_date"];
                $product = $row["product_name"];
                $prodDesc = $row["product_description"];?>
                
                <h2><?php echo $product?></h2>
                <h3><?php echo $prodDesc?></h3>
                <h3>Team Members:</h3>
                <ul style="list-style-type:none;padding:0;margin:0;">
                    <lr>Michael Harris</lr><br>
                    <lr>Thomas Personett</lr><br>
                    <lr>Nathan Deas</lr><br>
                    <lr>Tirth Patel</lr><br>
                </ul>
                <h3>Team Number: <?php echo $teamNum?></h3>
                <h3>Current Sprint Number: <?php echo $sprNum?></h3><?php
            
                // Display results as a table
                if ($result->num_rows > 0) {
                    echo "<h2>About Page Table</h2>";
                    echo "<table align='center'>";
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                    echo "<td><strong>" . $key . "</strong></td>";
                    }
                    echo "</tr>";
                    echo "<td>" . $sprNum . "</td>";
                    echo "<td>" . $teamNum . "</td>";
                    echo "<td>" . $releaseDate . "</td>";
                    echo "<td>" . $product . "</td>";
                    echo "<td>" . $prodDesc . "</td>";
                    // foreach ($row as $key => $value) {
                    // echo "<td>" . $value . "</td>";
                    // }
                    echo "</tr>";
                    while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<td>" . $value. "</td>";
                    }
                    echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No Result Returned";
                }

                $conn->close();
            }

            if(isset($_POST['button2']) || isset($_POST['submit_form'])) {
                if (isset($_POST['submit_form'])) {
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check database connection
                    if ($conn->connect_error) {
                        $message = "Connection failed: " . $conn->connect_error;
                    } else {
                        $sqlvalues = "VALUES ('". $_POST['version']. "', '". $_POST['teamNum']. "', '". $_POST['releaseDate']. "', '". $_POST['productName']. "', '". $_POST['prodDesc']. "');";

                        $sql = "INSERT INTO about (version, team_num, release_date, product_name, product_description) " . $sqlvalues;
                        
                        try {
                            $result = $conn->query($sql);
                            $message = "New Sprint Added";
                        } catch (mysqli_sql_exception $e) {
                            $message = "Error: <br>". $e->getMessage();
                        }
                    }

                    $conn->close();

                }

                echo "<h2>Add Sprint Entry</h2>";
                // Insert Form here (Text fields for each table entry, if empty do not update that field)?>
                <form action="$_SERVER['PHP_SELF']" method="POST">
                    <label for="version">Version:</label>
                    <input type="text" required="required" id="version" name="version" value="3"><br>
                    <label for="teamNum">Team number:</label>
                    <input type="text" required="required" id="teamNum" name="teamNum" value="25"><br>
                    <label for="releaseDate">Release date:</label>
                    <input type="text" required="required" id="releaseDate" name="releaseDate" value="2023-09-26"><br>
                    <label for="productName">Product name:</label>
                    <input type="text" required="required" id="productName" name="productName" value="Driver Incentive Service Webpage"><br>
                    <label for="prodDesc">Product description:</label>
                    <input type="text" required="required" id="prodDesc" name="prodDesc" value="Product Description"><br>
                    <div class="form-bottom">
                        <p class="message" name="message" style="width: 100%"><?php echo $message?></p>
                        <input type="submit" name="submit_form" class="button">
                    </div>
                </form><?php

            }
            
            
        ?>
    </body>
</html>
