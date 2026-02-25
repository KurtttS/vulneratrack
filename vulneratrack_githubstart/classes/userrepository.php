<?php 

class userrepository extends database {

    public function checkUser($email){
            $stmt = $this->getConnection()->prepare(
                'SELECT 1 FROM `user` WHERE Email = ? LIMIT 1'
            );

            $stmt->execute([$email]);

            return !$stmt->fetch();
        }
    
    public function passcheck($password, $confirmpass) {
        return $password === $confirmpass;
    }
}
?>