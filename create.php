<?php
// Include carDAO file
require_once('./dao/carDAO.php');

 
// Define variables and initialize with empty values
$brand = $number= $foundDate = $image = "";
$brand_err = $number_error = $foundDate_err = $image_err = "";
$carDAO = new carDAO(); 
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate brand
    $input_brand = trim($_POST["brand"]);
    if(empty($input_brand)){
        $brand_err = "Please enter a brand.";
    } elseif(!filter_var($input_brand, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $brand_err = "Please enter a valid brand.";
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
        //date must be smaller than today 
    } elseif($date_now < $input_foundDate) {
        $foundDate_err = "Found date shouldn't be greater than today.";
    } else {
        $foundDate = $input_foundDate;
    }
    
    // Upload image after all other validation passed
    if(empty($brand_err) && empty($foundDate_err)){
        $target_dir="images/";
        $target_file=$target_dir.basename($_FILES["carImage"]["name"]);
        // check value will be false if tmp_name file does not exist 
        if (!file_exists($_FILES['carImage']['tmp_name'])) {
            $image_err="please upload an image";
        } else {
                // move the image to target folder
            if (move_uploaded_file($_FILES["carImage"]["tmp_name"], $target_file)){
                $image=basename($_FILES["carImage"]["name"]); 
            } else {
                $image_err="upload file error";
            }
        }
    }
    
    // Check input errors before inserting in database
    if(empty($brand_err) && empty($foundDate_err) && empty($image_err) && empty($number_err)){  
        $car = new Car(0, $brand, $number, $foundDate, $image);
        $addResult = $carDAO->addCar($car);
        echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        header( "refresh:2; url=index.php" ); 
        // Close connection
        $carDAO->getMysqli()->close();
        }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       /* Css style */
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add car record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
                            <span class="invalid-feedback"><?php echo $brand_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Dealer Numbers in Canada</label>
                            <input type="text" name="number" class="form-control <?php echo (!empty($number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $number; ?>">
                            <span class="invalid-feedback"><?php echo $number_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Found Date</label>
                            <textarea name="foundDate" class="form-control <?php echo (!empty($foundDate_err)) ? 'is-invalid' : ''; ?>"><?php echo $foundDate; ?></textarea>
                            <span class="invalid-feedback"><?php echo $foundDate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Logo</label>
                            <br>
                            <input type="file" accept="image/*" id="carImage" name="carImage">
                            <span class="invalid-feedback d-block"><?php echo $image_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                        <!-- force the invalid-feeback to show by d-block, refer: https://stackoverflow.com/questions/48401656/bootstrap-4-0-invalid-feedback-doesnt-show -->
                    </form>
                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>