<?php
class users {

    public $id;
    public $full_name;
    public $password;
    public $email;
    public $isAdmin; // 0 ללקוח, 1 לאדמין

    public function __construct($id, $full_name, $password, $email, $isAdmin) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->password = $password;
        $this->email = $email;
        $this->isAdmin = $isAdmin;
    }

    public function addusers($dbConn) {
        $sql = "INSERT INTO users (id, full_name, password, email, admin) 
                VALUES ('$this->id', '$this->full_name', '$this->password', '$this->email', '$this->isAdmin')";
        if (mysqli_query($dbConn, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function isusersExist($dbConn) {
        $sql = "SELECT * FROM users WHERE id='$this->id' AND full_name='$this->full_name'";
        $result = mysqli_query($dbConn, $sql);
        return mysqli_num_rows($result) > 0;
    }

    // פונקציית התחברות סטטית
    public static function loginUser($dbConn, $id, $password) {
        $sql = "SELECT * FROM users WHERE id = '$id'";
        $result = mysqli_query($dbConn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row['password'] === $password) {
                return [
                    'id' => $row['id'],
                    'full_name' => $row['full_name'],
                    'isAdmin' => (int)$row['admin'] // ← כאן התיקון לפי שם העמודה במסד
                ];
            }
        }
        return false;
    }
}
?>