<?php
// Include employeeDAO file
require_once('./dao/carDAO.php');
$carDAO = new carDAO(); 

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    $car = $carDAO->getCar($id);
            
    if($car){
        // Retrieve individual field value
        $brand = $car->getBrand();
        $number=$car->getNumber();
        $foundDate = $car->getFoundDate();
        $image = $car->getImage();
    } else{
        // URL doesn't contain valid id. Redirect to error page
        header("location: error.php");
        exit();
    }
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
} 

// Close connection
$carDAO->getMysqli()->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Brand</label>
                        <p><b><?php echo $brand; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Found Date</label>
                        <p><b><?php echo $foundDate; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Dealer Numbers in Canada</label>
                        <p><b><?php echo $number; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <p><?php echo "<img src=\"images/".$image."\" width=\"200px\">"; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>