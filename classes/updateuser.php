<?php
class updateuser extends database {

    protected function setUserUpdate($userid, $firstname, $lastname, $email, $dateofbirth, $status, $password = null) {

        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $now = $now->format('Y-m-d H:i:s');

        // If password is provided, hash it and update
        if (!empty($password)) {
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->getConnection()->prepare(
                "UPDATE `user`
                 SET First_Name=?, Last_Name=?, Email=?,
                     Date_of_Birth=?, User_Status=?, Password_Hashed=?, Date_Updated=?
                 WHERE UserID=?"
            );

            $stmt->execute([
                $firstname,
                $lastname,
                $email,
                $dateofbirth,
                $status,
                $hashedpass,
                $now,
                $userid
            ]);
        } else {
 
            $stmt = $this->getConnection()->prepare(
                "UPDATE `user`
                 SET First_Name=?, Last_Name=?, Email=?,
                     Date_of_Birth=?, User_Status=?, Date_Updated=?
                 WHERE UserID=?"
            );

            $stmt->execute([
                $firstname,
                $lastname,
                $email,
                $dateofbirth,
                $status,
                $now,
                $userid
            ]);
        }

        return $now;
    }
}
?>