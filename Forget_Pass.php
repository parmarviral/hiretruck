<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>HireTruck - Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
  </head>
  <body>

<?php
require("connection.php");
require("Meiler/OTP.php");
require("Sms/way.php");
if(isset($_POST)){
if ($_SERVER["REQUEST_METHOD"] == "POST") //check if Post is created or not!!!
 {
    $secret = '6LfX7nQUAAAAAM5UR8XIuOEStN2S5UtE3xWOhRK9'; //our secret key generated by Google
    $response = @$_POST['g-recaptcha-response']; //responce from google inn variable
    $remoteip = $_SERVER['REMOTE_ADDR']; //checking ip address
    @$url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip"); //pass data to goole to verify im not robot!!
    $result = json_decode($url,TRUE); //we get responce from google in jason connvert them in array and gat the result
    //print_r($result); // print array from responce and convert JASON output to php!!
    if ($result['success'] == 1) //check if result is sucess or not??
    {
      if(!$_POST['radioF']){
        header("refresh:4;url=Forgot_pass.php");
      }
  @$Usr_typ = mysqli_real_escape_string($con, htmlspecialchars($_POST['radioF']));
  $mail_add = mysqli_real_escape_string($con, htmlspecialchars($_POST['User_email']));
  //strtolower($mail_add);
  $Sec_Que = mysqli_real_escape_string($con, htmlspecialchars($_POST['Select']));
  $Sec_Ans = mysqli_real_escape_string($con, htmlspecialchars($_POST['User_Ans']));
  $eemail=$mail_add;
  $num=md5(rand(1,100000));
  $password=substr($num,-8);
  if($Usr_typ == "Shipper"){
      $query = "SELECT * FROM user_s WHERE S_mail='$mail_add' AND S_status='0' AND S_active='0'and S_security_question='$Sec_Que' and S_security_answer='$Sec_Ans'";
      $sql = mysqli_query($con, $query) or die(mysqli_error($con));
      $count = mysqli_num_rows($sql);
      //echo $count;exit;
      if ($count != 0) {
        while($rw=mysqli_fetch_array($sql)){
          $fname = $rw['S_fname'];
          $lname = $rw['S_lname'];
          $num = $rw['S_mnumber'];
        }
        $eename = $fname."<' '>".$lname;
        $Up_qry="UPDATE `user_s` SET `S_password`='$password' WHERE S_mail='$eemail'";
        $up_sql=mysqli_query($con,$Up_qry) or die(mysqli_error($con));
        if($up_sql){
          echo "<div class='container'> <div class='alert alert-success' role='alert' style='text-align:center; margin-top:25%;padding-top:2%;padding-bottom:2%' ></h4> <strong>Well done You are Almost There!</strong> Now Check Your MailBox or Mobile For the Password!!</h4></div> </div>";
          otp($eemail,$eename,$password);
          otpmob($eename,$password,$num);
          }
          header("refresh:4;url=login.php");
          }
          else
          {
              echo "<h1 style='text-align:center;padding-top:9%;'>Opps! it's seems you Given Wrong Credentials</h1>";
              header("refresh:4;url=Forgot_pass.php");
          }
        }
      if($Usr_typ == "Transport"){
        $query = "SELECT * FROM user_t WHERE T_mail='$mail_add' AND T_status='0' AND T_active='0'and T_security_question='$Sec_Que' and T_security_answer='$Sec_Ans'";
        $sql = mysqli_query($con, $query) or die(mysqli_error($con));
        $count = mysqli_num_rows($sql);
        //echo $count;exit;
        if ($count != 0) {
          while($rw=mysqli_fetch_array($sql)){
            $eename = $rw['T_owner_name'];
            $num = $rw['T_number'];
            $eemail=$mail_add;
          }
          $Up_qry="UPDATE `user_t` SET `T_password`='$password' WHERE T_mail='$eemail'";
          $up_sql=mysqli_query($con,$Up_qry) or die(mysqli_error($con));
          if($up_sql){
            echo "<div class='container'> <div class='alert alert-success' role='alert' style='text-align:center; margin-top:25%;padding-top:2%;padding-bottom:2%' ></h4> <strong>Well done You are Almost There!</strong> Now Check Your MailBox or Mobile For the Password!!</h4></div> </div>";
            otp($eemail,$eename,$password);
            otpmob($eename,$password,$num);
            }
            header("refresh:4;url=login.php");
            }
            else
            {
                echo "<h1 style='text-align:center;padding-top:9%;'>Opps! it's seems you Given Wrong Credentials</h1>";
                header("refresh:4;url=Forgot_pass.php");
            }
      }
    }
    else
    {
      echo "<div class='container'> <div class='alert alert-danger' role='alert' style='text-align:center; margin-top:25%;padding-top:2%;padding-bottom:2%' ><h4> <strong>Ohh Snap!!!</strong>it's seems you Forgot to Fill-up CAPTCHA</h4></div> </div>";
      header( "refresh:3;url=Forgot_pass.php" );
    }
  }
  else {
          header("location:index.php");
        }
}
else
{
  header("location:index.php");
}
 ?>
   </body>
   </html>