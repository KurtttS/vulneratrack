<?php
    class signupuserctrl extends signupuser{

    private $firstname;
    private $lastname;
    private $address;
    private $email;
    private $password;
    private $confirmpass;
    private $dateofbirth;
    private $birthcert;
    private $userstatus;
    private $householdmode;
    private $joincode = null;
    private $longitude;
    private $latitude;
    private $area;

    public function __construct($firstname,$lastname,$address,$email,$password,$confirmpass,$dateofbirth,$birthcert,$userstatus,
    $householdmode,$joincode,$longitude, $latitude, $area){
        parent::__construct();
        $this->firstname=$firstname;
        $this->lastname=$lastname;
        $this->address=$address;
        $this->email=$email;
        $this->password=$password;
        $this->confirmpass=$confirmpass;
        $this->dateofbirth=$dateofbirth;
        $this->birthcert=$birthcert;
        $this->userstatus=$userstatus;
        $this->householdmode=$householdmode;
        $this->joincode=$joincode;
        $this->longitude=$longitude;
        $this->latitude=$latitude;
        $this->area=$area;
    }

    public function signupuser() {
        if($this->emptyinput() == false){
            header("location:../loginreg-module/signup.php?error=emptyinput");
            exit();
        }
        if($this->usernamecheck() == false){
            header("location:../loginreg-module/signup-con.php?error=incorrectusername");
            exit();
        }

        return $this->setUser($this->firstname,$this->lastname,$this->address,$this->email,$this->password,$this->dateofbirth,$this->birthcert,$this->userstatus,
        $this->householdmode,$this->joincode,$this->longitude,$this->latitude,$this->area);
    }

    private function emptyinput(){
        $result;
        if(empty($this->firstname) || empty($this->lastname) || empty($this->address) || empty($this->email) || empty($this->password) || empty($this->confirmpass) || empty($this->dateofbirth) 
            || empty($this->userstatus)){
            $result = false;
        } 

        else {
            $result =  true;
        }

        return $result;
    }

    private function usernamecheck() {
        $result;
       if (!preg_match('/^[a-zA-Z\s\-]{2,50}$/', $this->firstname) || !preg_match('/^[a-zA-Z\s\-]{2,50}$/', $this->lastname)) {
            $result = false;
        } 

        else {
            $result =  true;
        }

        return $result;
    }

    }
?>