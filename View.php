<?php
include("includes/config.php");
include("functions.php"); 

$Keyword = $_GET['Keyword'];
$Number = $_GET['Number'];
$Average = $_GET['Average'];
$Average2 = ceil(5 - $Average);
$bgURL = fetchBackgroundImage($Keyword);  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis </title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">
    <style>
        body {
            background-image: url('<?php echo $bgURL; ?>');
            background-size: cover;
            background-repeat: no-repeat;
        }

#auth-left {
    padding: 50px;
    border-radius: 10px;
}

.auth-title, h2 {
    color: #333;  /* Dark color for better visibility */
    text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.7);  /* Shadow for better readability */
}

.table {
    background-color: rgba(255, 255, 255, 0.6);  /* Semi-transparent background for readability */
}

.star-icon {
    font-size: 70px;  /* Increased size */
    color: #FFD700;  /* Bright gold color */
}


.simple-datatables .pagination li {
    background-color: rgba(255, 255, 255, 0.6);
    border-radius: 4px;
    margin: 0 2px;
}

.simple-datatables .pagination li:hover, .simple-datatables .pagination li.active {
    background-color: rgba(0, 86, 179, 0.7);
    color: #FFF;
}


.simple-datatables .datatable-search {
    padding: 10px 15px;
    border: 1px solid rgba(0, 86, 179, 0.5);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;  
    background-color: rgba(255, 255, 255, 0.8);  
    border-radius: 4px; 
}

.simple-datatables .datatable-search::placeholder {
    color: #777;
}


.simple-datatables .pagination li a, .simple-datatables .datatable-info, .simple-datatables .datatable-dropdown, .simple-datatables .datatable-search, .simple-datatables .datatable-pager {
    background-color: rgba(255, 255, 255, 0.6);  
    border: none;
    color: #333;
}

.simple-datatables .pagination li:hover a {
    background-color: rgba(0, 86, 179, 0.7);
    color: #FFF;
}

.simple-datatables .pagination li.active a {
    background-color: rgba(0, 86, 179, 0.9);
    color: #FFF;
}

#table1 thead th {
    background-color: rgba(0, 86, 179, 0.7);
    color: #FFF;
}

#table1 tbody td {
    background-color: rgba(255, 255, 255, 0.6);
    color: #333;
}

 </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-12 col-12">
                <div id="auth-left">
                    <center>
                        <h1 class="auth-title">Sentiment Analysis</h1>
                        <h2>Search Result For (<?php echo $Keyword; ?>):</h2>
                        <...
<?php
    for ($i=1; $i<=$Average; $i++){
        echo '<i class="bi bi-star-fill star-icon"></i>';
    }
    for ($i=1; $i<=$Average2; $i++){
        echo '<i class="bi bi-star star-icon"></i>';
    }
?>
...

                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Text</th>
                                    <th>Sentiment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql1 = mysqli_query($dbConn,"select * from tweets order by ID DESC");
                                    while ($row1 = mysqli_fetch_array($sql1)){
                                        $ID = $row1['ID'];
                                        $Text = $row1['Text'];
                                        $Sentiment = $row1['Sentiment'];
                                        echo "<tr class='grade'>";
                                        echo "<td>$ID</td>";
                                        echo "<td>$Text</td>";
                                        echo "<td>$Sentiment</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        <br><br>
                        Sentiment Analysis  © 2023. All Rights Reserved.
                    </center>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script>
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
</body>
</html>
