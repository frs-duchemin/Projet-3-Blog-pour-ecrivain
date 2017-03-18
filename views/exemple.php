<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$limit = 3;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $limit;

$sql = "SELECT * FROM employee ORDER BY id ASC LIMIT $start_from, $limit";
$rs_result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="dist/simplePagination.css" />
    <script src="dist/jquery.simplePagination.js"></script>
    <title></title>
    <script>
    </script>
</head>
<body>
<div class="container" style="padding-top:20px;">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Salary</th>
            <th>Age</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($rs_result)) {
            ?>
            <tr>
                <td><?php echo $row["employee_name"]; ?></td>
                <td><?php echo $row["employee_salary"]; ?></td>
                <td><?php echo $row["employee_age"]; ?></td>
            </tr>
            <?php
        };
        ?>
        </tbody>
    </table>

    <?php
    $sql = "SELECT COUNT(id) FROM employee";
    $rs_result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($rs_result);
    $total_records = $row[0];
    $total_pages = ceil($total_records / $limit);
    $pagLink = "<nav><ul class='pagination'>";
    for ($i=1; $i<=$total_pages; $i++) {
        $pagLink .= "<li><a href='index.php?page=".$i."'>".$i."</a></li>";
    };
    echo $pagLink . "</ul></nav>";
    ?>




</div>
</body>
</html>




<script type="text/javascript">
    $(document).ready(function(){
        $('.pagination').pagination({
            items: <?php echo $total_records;?>,
            itemsOnPage: <?php echo $limit;?>,
            cssStyle: 'light-theme',
            currentPage : <?php echo $page;?>,
            hrefTextPrefix : 'index.php?page='
        });
    });
</script>