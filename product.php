<?php
class product {

    public $SN;
    public $name;
    public $amount;
    public $price;
    public $sizes;


    public function __construct($SN, $name, $amount, $price, $sizes	) {
        $this->SN = $SN;
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
        $this->sizes = $sizes;
        
    }


    public function addusers($dbConn) {
        $sql = "INSERT INTO users (SN, name, amount, price,sizes) 
                VALUES ('$this->SN', '$this->name', '$this->amount', '$this->price','$this->sizes')";
        if (mysqli_query($dbConn, $sql)) {
            return true;
        } else {
            return false;
        }
    }


    public function isusersExist($dbConn) {
        $sql = "SELECT * FROM users WHERE SN='$this->SN' AND name='$this->name'";
        $result = mysqli_query($dbConn, $sql);
        return mysqli_num_rows($result) > 0;
    }
}
?>