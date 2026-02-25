<?php
class updateuserctrl extends updateuser {

    private $userid;
    private $firstname;
    private $lastname;
    private $email;
    private $dateofbirth;
    private $status;
    private $password;
    private $confirmpass;


    public function __construct($userid, $firstname, $lastname, $email, $dateofbirth, $status, $password = null, $confirmpass = null){
        parent::__construct();
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->dateofbirth = $dateofbirth;
        $this->status = $status;
        $this->password = $password;
        $this->confirmpass = $confirmpass;
    }

    public function updateUser(){
        if(!$this->passwordMatch()){
            header("location: ../user-module/profile.php?error=passwordnotmatch");
            exit();
        }

       
        return $this->setUserUpdate(
            $this->userid,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->dateofbirth,
            $this->status,
            $this->password 
        );
    }

    private function passwordMatch(){
        if(!empty($this->password)){
            if($this->password !== $this->confirmpass){
                return false;
            }
        }
        return true;
    }
}
?>
