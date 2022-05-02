<?php
// Include carDAO file
require_once('./dao/carDAO.php');
 
// Define variables and initialize with empty values
$brand = $number = $foundDate = $image = "";
$brand_err = $number_err= $foundDate_err = $image_err = "";
$carDAO = new carDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate brand
    $input_brand = trim($_POST["brand"]);
    if(empty($input_brand)){
        $brand_err = "Please enter a brand.";
    } elseif(!filter_var($input_brand, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $brand_err = "Please enter a valid brand name.";
    } else{
        $brand = $input_brand;
    }
        
    // Validate number
    $input_number = trim($_POST["number"]);
    if(empty($input_number)){
        $number_err="Please enter a total number of dealers.";
    }elseif($input_number<0){
        $number_err="Please enter a positive total number of dealers.";
    }else{
        $number= $input_number;
    }

    // Validate found date
    $date_now = date("Y-m-d");
    $input_foundDate = trim($_POST["foundDate"]);
    if(empty($input_foundDate)){
        $foundDate_err = "Please enter an found date.";     
    } elseif(!$carDAO->validateDate($input_foundDate)){
        $foundDate_err = "Please enter date with format: YYYY-MM-DD.";
        // date must be smaller than today
    } elseif($date_now < $input_foundDate) {
        $foundDate_err = "Found date shouldn't be greater than today.";
    } else {
        $foundDate = $input_foundDate;
    }
    
    // Upload image after all other validation passed
    if(empty($brand_err) && empty($foundDate_err)){
        $target_dir="images/";
        $target_file=$target_dir.basename($_FILES["carImage"]["name"]);
        // only upload and update db when tmp_name file selected 
        if (file_exists($_FILES['carImage']['tmp_name'])) {    
            if (move_uploaded_file($_FILES["carImage"]["tmp_name"], $target_file)){
                $image=basename($_FILES["carImage"]["name"]); 
            } else {
                $image_err="upload file error";
            }
        }
    }
    
    
    // Check input errors before inserting in database
    if(empty($brand_err) && empty($foundDate_err) && empty($image_err) && empty($number_err)){   
        $car = new Car($id, $brand, $number, $foundDate, $image);
        $result = $carDAO->updateCar($car);
        echo '<br><h6 style="text-align:center">' . $result . '</h6>';   
        header( "refresh:2; url=index.php" ); 
        // Close connection
        $carDAO->getMysqli()->close();
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        $car = $carDAO->getCar($id);
                
        if($car){
            // Retrieve individual field value
            $brand = $car->getBrand();
            $number= $car->getNumber();
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
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                     <!--enctype required for upload file， refer：https://stackoverflow.com/questions/11200843/php-undefined-index-error-files -->
                    <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
                            <span class="invalid-feedback"><?php echo $brand_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Found Date</label>
                            <textarea name="foundDate" class="form-control <?php echo (!empty($foundDate_err)) ? 'is-invalid' : ''; ?>"><?php echo $foundDate; ?></textarea>
                            <span class="invalid-feedback"><?php echo $foundDate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Dealer Numbers in Canada</label>
                            <input type="text" name="number" class="form-control <?php echo (!empty($number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $number; ?>">
                            <span class="invalid-feedback"><?php echo $number_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Logo</label>
                            <br>
                            <input type="file" accept="image/*" id="carImage" name="carImage">
                            <span class="invalid-feedback d-block"><?php echo $image_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>