
--
-- Database: `manufacture` and php web application user
CREATE DATABASE manufacture;
GRANT USAGE ON *.* TO 'appuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON manufacture.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

USE manufacture;
--
-- Table structure for table `cars`
--

CREATE TABLE IF NOT EXISTS `cars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dealer_numbers` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `found_date` date NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `dealer_numbers`, `brand`, `found_date`, `image`) VALUES
(1, '50', 'BMW', '1916-03-07', 'BMW.jpg');
INSERT INTO `cars` (`id`, `dealer_numbers`, `brand`, `found_date`, `image`) VALUES
(2, '56', 'BENZ', '1926-06-28', 'BENZ.png');
INSERT INTO `cars` (`id`, `dealer_numbers`, `brand`, `found_date`, `image`) VALUES
(3, '49', 'AUDI', '1909-07-16', 'AUDI.png');


