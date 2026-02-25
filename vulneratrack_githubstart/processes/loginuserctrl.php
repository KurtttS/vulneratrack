<?php
    class loginuserctrl extends loginuser{

    private $logemail;
    private $logpass;

    public function __construct($logemail,$logpass){
        parent::__construct();
        $this->logemail=$logemail;
        $this->logpass=$logpass;
    }

    public function loginuser() {   
        if($this->emptyinput() == false){
            header("location:../index.php?error=emptyinput");
            exit();
        }
        return $this->loguser($this->logemail, $this->logpass);

    }

    private function emptyinput(){
        $result;
        if(empty($this->logemail) || empty($this->logpass)){
            $result = false;
        } 
        else {
            $result =  true;
        }
        return $result;
    }

    }
?>