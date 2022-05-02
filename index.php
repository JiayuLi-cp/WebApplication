<!-- index page for assignment 2 -->
<?php require_once('./dao/carDAO.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- css style -->
    <style>
        .wrapper{
            width: 620px;
            margin: 0 auto; 
        }
        .table_header {
                background-color: transparent;
                border: none;
                cursor: pointer;
                
            }
        button:focus {outline:0;}
        table tr td:last-child{
            width: 150px;
        }
    </style>
    <script src="sort.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="car">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Automobile Manufactures</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Car</a>
                    </div>
                    <?php
                        $carDAO = new carDAO();
                        $cars = $carDAO->getCars();
                        // if cars element is not null show the following content
                        if($cars){
                            echo '<table id="carTable" class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo '<th><button class="table_header">#</button></th>';
                                        echo '<th><button class="table_header">Brand</button></th>';
                                        echo '<th><button class="table_header">Dealer Numbers(CA)</button></th>';
                                        echo '<th><button class="table_header">Found Date</button></th>';
                                        echo '<th>Logo</th>';
                                        echo '<th>Action</th>';
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                // ehco the result for each car elements inside cars array
                                foreach($cars as $car){
                                    echo "<tr>";
                                        echo "<td>" . $car->getId(). "</td>";
                                        echo "<td>" . $car->getBrand() . "</td>";
                                        echo "<td>" . $car->getNumber() . "</td>";
                                        echo "<td>" . $car->getFoundDate() . "</td>";
                                        echo "<td><div><img src=\"images/".$car->getImage()."\" width=\"100px\"><div></td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id='. $car->getId() .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id='. $car->getId() .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $car->getId() .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            //$result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                   
                    // Close connection
                    $carDAO->getMysqli()->close();
                    include 'footer.php';
                    ?>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>
