<?php
session_start();
include('doctor/includes/dbconnection.php');
if(isset($_POST['submit']))
{
    $name=$_POST['name'];
    $mobnum=$_POST['phone'];
    $email=$_POST['email'];
    $appdate=$_POST['date'];
    $aaptime=$_POST['time'];
    $specialization=$_POST['specialization'];
    $doctorlist=$_POST['doctorlist'];
    $message=$_POST['message'];
    $aptnumber=mt_rand(100000000, 999999999);
    $cdate=date('Y-m-d');
    if($appdate<=$cdate){
        echo '<script>alert("Appointment date must be greater than todays date")</script>';
    } 
    else{
        $sql="insert into tblappointment(AppointmentNumber,Name,MobileNumber,Email,AppointmentDate,AppointmentTime,Specialization,Doctor,Message)values(:aptnumber,:name,:mobnum,:email,:appdate,:aaptime,:specialization,:doctorlist,:message)";
        $query=$dbh->prepare($sql);
        $query->bindParam(':aptnumber',$aptnumber,PDO::PARAM_STR);
        $query->bindParam(':name',$name,PDO::PARAM_STR);
        $query->bindParam(':mobnum',$mobnum,PDO::PARAM_STR);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->bindParam(':appdate',$appdate,PDO::PARAM_STR);
        $query->bindParam(':aaptime',$aaptime,PDO::PARAM_STR);
        $query->bindParam(':specialization',$specialization,PDO::PARAM_STR);
        $query->bindParam(':doctorlist',$doctorlist,PDO::PARAM_STR);
        $query->bindParam(':message',$message,PDO::PARAM_STR);
        $query->execute();
        $LastInsertId=$dbh->lastInsertId();
        if ($LastInsertId>0) {
            echo '<script>alert("Your Appointment Request Has Been Send. We Will Contact You Soon")</script>';
            echo "<script>window.location.href ='index.php'</script>";
        }
        else
        {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
        <title>DocTime Scheduler || Home Page</title> 
        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
       <link href="css/owl.carousel.min.css" rel="stylesheet">
        <link href="css/owl.theme.default.min.css" rel="stylesheet">
        <link href="css/templatemo-medic-care.css" rel="stylesheet">
        <script>
        function getdoctors(val) 
        {
            $.ajax({
            type: "POST",
            url: "get_doctors.php",
            data:'sp_id='+val,
            success: function(data){
            $("#doctorlist").html(data);
            }
            });
        }
    </script>
</head>
<body id="top">
<main>
    <?php include_once('includes/header.php');?>
    <section class="gallery"style="padding-top:55px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-6 ps-0">
                    <img src="images/gallery/medium-shot-man-getting-vaccine.jpg" class="img-fluid galleryImage" alt="get a vaccine" title="get a vaccine for yourself">
                </div>
                <div class="col-lg-6 col-6 pe-0">
                    <img src="images/gallery/female-doctor-with-presenting-hand-gesture.jpg" class="img-fluid galleryImage" alt="wear a mask" title="wear a mask to protect yourself">
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <h2 class="mb-lg-3 mb-3">About US</h2>
                    <p><b>Welcome to our Doctor Appointment System, where your health is our priority. Our platform is designed with the utmost care and consideration to simplify your healthcare experience.</b></p>
                    <p><b>Our dedicated team is committed to providing you with a reliable and efficient platform, empowering you to take control of your health journey.</b></p>
                    <p><b>Trust us to connect you with skilled healthcare professionals, making your appointments hassle-free and ensuring you receive the care you deserve. Your well-being is at the heart of what we do, and we look forward to being your partner in maintaining a healthy and happy life.</b></p>
                </div>
                <div class="col-lg-4 col-md-5 col-12 mx-auto">
                    <div class="featured-circle bg-white shadow-lg d-flex justify-content-center align-items-center">
                        <p class="featured-text"><span class="featured-number">12</span> Years<br> of Experiences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding" id="booking">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 mx-auto">
                    <div class="booking-form"> 
                        <h2 class="text-center mb-lg-3 mb-2">Book an appointment</h2>
                        <form role="form" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Full name" required='true'>
                                </div>
                                <div class="col-lg-6 col-12">
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email address" required='true'>
                                </div>                                 
                                <div class="col-lg-6 col-12">
                                <input type="telephone" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" maxlength="10">
                                </div>
                                <div class="col-lg-6 col-12">
                                <input type="date" name="date" id="date" value="" class="form-control">                                           
                                </div>
                                <div class="col-lg-6 col-12">
                                <input type="time" name="time" id="time" value="" class="form-control">                                          
                                </div>
                                <div class="col-lg-6 col-12">
                                    <select onChange="getdoctors(this.value);"  name="specialization" id="specialization" class="form-control" required>
                                    <option value="">Select specialization</option>
                                    <!--- Fetching States--->
                                    <?php
                                    $sql="SELECT * FROM tblspecialization";
                                    $stmt=$dbh->query($sql);
                                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                    while($row =$stmt->fetch()) 
                                    { 
                                    ?>
                                    <option value="<?php echo $row['ID'];?>"><?php echo $row['Specialization'];?></option>
                                    <?php 
                                    }?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <select name="doctorlist" id="doctorlist" class="form-control">
                                    <option value="">Select Doctor</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="5" id="message" name="message" placeholder="Additional Message"></textarea>
                                </div>
                                <div class="col-lg-3 col-md-4 col-6 mx-auto">
                                    <button type="submit" class="form-control" name="submit" id="submit-button">Book Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include_once('includes/footer.php');?>
<!-- JAVASCRIPT FILES -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/scrollspy.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>