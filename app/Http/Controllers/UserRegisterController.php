<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserRegisterController extends Controller
{
    protected $response ="";
    protected $level    =0;
    public function index(Request $request){
        /**
         * get AT parameters
         */
        $phoneNumber    =$request->get('phoneNumber');
        $sessionId      =$request->get('sessionId');
        $serviceCode    =$request->get('serviceCode');
        $text           =$request->get('text');

        $exploded_ussd_string=explode("*",$text);

        $this->level=count($exploded_ussd_string);

        if($text==""){
           $this->display_menu();
        }
        elseif ($text=="1"){
            $this->response="<strong>Register for Cisco cyber security</strong>\n";
            $this->response.="1:Enter your first name:-";
            $this->ussd_proceed($this->response);
        }
        elseif (($exploded_ussd_string[0]==1)  && ($this->level==2)){
            $this->response="2:Enter your last name";
            $this->ussd_proceed($this->response);
        }
        elseif (($exploded_ussd_string[0]==1) && ($this->level==3)){
            $this->response="3:Enter your school email address";
            $this->ussd_proceed($this->response);

        }
        elseif (($exploded_ussd_string[0]==1) && ($this->level==4)){

            $first_name =$exploded_ussd_string[1];
            $last_name  =$exploded_ussd_string[2];
            $email      =$exploded_ussd_string[3];
            $phone      =$phoneNumber;

            User::create([
                'first_name'    =>$first_name,
                'last_name'     =>$last_name,
                'email'         =>$email,
                'phone'         =>$phone
            ]);
            $this->response="Congratulations <strong>$first_name $last_name </strong>you have successfully registered for online classes";

            $this->ussd_stop($this->response);
        }

    }
    public function display_menu(){
        $this->response.="Welcome to jkuat online classes\n";
        $this->response.="1:Register\n";
        $this->response.="2:About us";

        //proceed
        $this->ussd_proceed($this->response);
    }

    public function ussd_proceed($continue){
        echo "CON $continue";
    }
    public function ussd_stop($end_text){
        echo "END $end_text";
    }
    public function about($about_us){
        $about_us="Thank you for visiting JKUAT,the leading university in Africa!";
        $this->ussd_stop($about_us);
    }
}
