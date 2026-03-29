<?php
class Cart {

    public $SN;
    public $id;
    public $amount_cart;

    public function __construct($SN, $id, $amount_cart) {
        $this->SN = $SN;
        $this->id = $id;
        $this->amount_cart = $amount_cart;
    }

    // הוספה לעגלת קניות
    public function addToCart($dbConn) {
        $sql = "INSERT INTO cart (SN_FK, id_FK, amount_cart) 
                VALUES ('$this->SN', '$this->id', '$this->amount_cart')";
        if (mysqli_query($dbConn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    // בדיקה אם המוצר כבר קיים בעגלה של המשתמש
    public function isInCart($dbConn) {
        $sql = "SELECT * FROM cart WHERE SN_FK = '$this->SN' AND id_FK = '$this->id'";
        $result = mysqli_query($dbConn, $sql);
        return mysqli_num_rows($result) > 0;
    }
}
?>
