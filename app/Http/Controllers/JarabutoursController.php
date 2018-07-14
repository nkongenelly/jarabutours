<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\TestEmail;
use App\Http\Requests\ContactFormRequest;
use App\Admin;
use Sendgrid;
use Session;

//require 'vendor/autoload.php';


class JarabutoursController extends Controller
{
    //
       /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function main()
    {
        return view('layouts.master');

    }
    
    public function response(array $errors)
    {
        //This will always return JSON object error messages
        return new JsonResponse($errors, 422);
    }

    public function sendEmail(Request $request){
        require 'sendgrid-php/vendor/autoload.php'; // If you're using Composer (recommended)
        // Comment out the above line if not using Composer
        //require("./sendgrid-php.php"); 
        // If not using Composer, uncomment the above line
        $apiKey = env('SENDGRID_API_KEY');
        // $authHeaders = [
        //     'Authorization: Bearer ' . $apiKey
        // ];
        // $client = new SendGrid\Client('https://api.sendgrid.com', $authHeaders);
        // $param = 'foo';
        // $response = $client->your()->api()->_($param)->call()->get();
        
        // var_dump(
        //     $response->statusCode(),
        //     $response->headers(),
        //     $response->body()
        // );
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $from1 = $request->email;
        //$to1 = "nkongenelly94@gmail.com";
        $subject1 = $request->subject;
        $body = $request->message;
        // $host = env('MAIL_HOST');
        // $username = env('MAIL_USERNAME');
        // $password = env('MAIL_PASSWORD');

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom($from1, "Client");
        $email->setSubject($subject1);
        $email->addTo("jarabu@jarabutours.com", "Jarabu Tours & Trravel");
        $email->addContent("text/plain", $body);

        
        $sendgrid = new \SendGrid($apiKey);
        $response = $sendgrid->client->mail()->send()->post($sendgrid);
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        Session::flash('success', 'Thank You ! Your email has been delivered.');
       // var_dump($email);
    }


 
    public function sendEmailss(Request $request){
        require_once("sendgrid-php/sendgrid-php.php");
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);
        $from1 = $request->email;
        $to1 = "nkongenelly94@gmail.com";
        $subject1 = $request->subject;
        $body = $request->message;
        $host = env('MAIL_HOST');
        $username = env('MAIL_USERNAME');
        $password = env('MAIL_PASSWORD');
       
        $headers = array('From' => $from,
                            'To' =>$to,
                            'Subject' =>$subject );   

        $smtp = Mail::to('smtp',array('host' => $host,
                                            'auth' => true,
                                            'username' => $username,
                                            'password' => $password)
                            );
        //$mail = $smtp ->send($to, $headers, $body);
        $mail = Mail::to('nkongenelly94@gmail.com')->send($to, $headers, $body);
        if($mail->statusCode() == 202){
            echo "Email sent successfully";
        }else{
            echo "Email could not be sent";
        }
       

        echo $response->statusCode();
        var_dump($response->headers());
        echo $response->body(); 

        

    }

    // public function mailToAdmin(ContactFormRequest $message, Admin $admin)
	// {        //send the admin an notification
	// 	$admin->notify(new InboxMessage($message));
	// 	// redirect the user back
	// 	return redirect()->back()->with('message', 'thanks for the message! We will get back to you soon!');
    // }
    
    // public function testing(Request $request){
    //         $this->validate($request,[
    //             'name' => 'required',
    //             'email' => 'required',
    //             'subject' => 'required',
    //             'message' => 'required'
    //         ]);
    //         $getx = $request->email;
    //         $getx = $request->name;
    //         $getx = $request->subject;
    //         $getx = $request->message;
           

    //         var_dump($getx);
    // }

}
