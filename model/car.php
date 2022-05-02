<?php
	class Car{		

		private $id;
		private $brand;
		private $number;
		private $foundDate;
		private $image;
				
		function __construct($id, $brand, $number, $foundDate, $image){
			$this->setId($id);
			$this->setBrand($brand);
			$this->setNumber($number);
			$this->setFoundDate($foundDate);
			$this->setImage($image);
			}

		public function getNumber(){
			return $this->number;
		}
		
		public function setNumber($number){
			$this->number = $number;
		}	
			
		public function getBrand(){
			return $this->brand;
		}
		
		public function setBrand($brand){
			$this->brand = $brand;
		}
		
		public function getFoundDate(){
			return $this->foundDate;
		}
		
		public function setFoundDate($foundDate){
			$this->foundDate = $foundDate;
		}

		public function getImage(){
			return $this->image;
		}

		public function setImage($image){
			$this->image = $image;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

	}
?>