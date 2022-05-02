<?php
require_once('abstractDAO.php');
require_once('./model/car.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
class carDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }  

    // validate Date function
    public function validateDate($inputDate){
        $format = 'Y-m-d';
        $dt = DateTime::createFromFormat($format, $inputDate);
        return $dt && $dt->format($format) === $inputDate;
    }
    
    // get each car element based the car id
    public function getCar($carId){
        $query = 'SELECT * FROM cars WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $car = new Car($temp['id'],$temp['brand'], $temp['dealer_numbers'], $temp['found_date'], $temp['image']);
            $result->free();
            return $car;
        }
        $result->free();
        return false;
    }

    // get all car elements in an array 
    public function getCars(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM cars');
        $cars = Array();
        
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){
                //Create a new car object, and add it to the array.
                $car = new Car($row['id'], $row['brand'], $row['dealer_numbers'], $row['found_date'], $row['image']);
                $cars[] = $car;
            }
            $result->free();
            return $cars;
        }
        $result->free();
        return false;
    }   
    
    // add a car to database
    public function addCar($car){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
			$query = 'INSERT INTO cars (brand, dealer_numbers, found_date, image) VALUES (?,?,?,?)';
			$stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $brand = $car->getBrand();
                    $number=$car->getNumber();
			        $foundDate = $car->getFoundDate();
			        $image = $car->getImage();
                  
			        $stmt->bind_param('siss', 
				        $brand,
                        $number,
				        $foundDate,
				        $image
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $car->getBrand() . ' added successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   

    // update a car in database
    public function updateCar($car){
        
        if(!$this->mysqli->connect_errno){
            //The query uses the question mark (?) as a
            //placeholder for the parameters to be used
            //in the query.
            //The prepare method of the mysqli object returns
            //a mysqli_stmt object. It takes a parameterized 
            //query as a parameter.
            $image = $car->getImage();
            $query = empty($image) ? "UPDATE cars SET brand=?, dealer_numbers=?, found_date=? WHERE id=?" : "UPDATE cars SET brand=?, dealer_numbers=?, found_date=?, image=? WHERE id=?";
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $id = $car->getId();
                    $brand = $car->getBrand();
                    $number= $car->getNumber();
			        $foundDate = $car->getFoundDate();
                    if(empty($image)){
                        $stmt->bind_param('sisi', 
				        $brand,
                        $number,
				        $foundDate,
                        $id);
                    } else {
                        $stmt->bind_param('sissi', 
				        $brand,
                        $number,
				        $foundDate,
				        $image,
                        $id);  
                    }
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $car->getBrand() . ' updated successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   

    // delete a car from database
    public function deleteCar($carId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM cars WHERE id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $carId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return $this->resetAutoIncrement($carId);
            }
        } else {
            return false;
        }
    }

    // auto increase the car id
    private function resetAutoIncrement($id){
        if(!$this->mysqli->connect_errno){
            if($this->mysqli->query('ALTER TABLE cars AUTO_INCREMENT = 1')){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>

