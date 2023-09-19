<?php
include("includes/config.php");
include("functions.php"); 

$bgURL = "default_background.jpg";  

if(isset($_POST['Keyword']) && isset($_POST['Number'])) {
    $Keyword = $_POST['Keyword'];
    $Number = $_POST['Number'];
    $bgURL = fetchBackgroundImage($Keyword);  

    $pythonPath = 'C:\Users\user\AppData\Local\Programs\Python\Python311\python.exe';
    
    $pythonScript = 'Final.py';
    $command = $pythonPath . ' ' . $pythonScript . ' ' . $Keyword . ' ' . $Number;
    $output = shell_exec($command);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis </title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <style>
        body {
            background-image: url('<?php echo $bgURL; ?>');
            background-size: cover;
            background-repeat: no-repeat;
        }
        #auth-left {
            padding: 50px;
            border-radius: 10px;
        }
        .auth-title, .auth-subtitle {
            color: #333; 
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.7); 
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.6); 
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-12 col-12">
                <div id="auth-left" class="text-center">
                    <h1 class="auth-title">Sentiment Analysis </h1>
                    <h2 class="auth-subtitle">Enter Keyword & Tweets Number</h2>
                    <form id="myForm" class="mt-5">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" id="Keyword" name="Keyword" required placeholder="Keyword">
                            <div class="form-control-icon">
                                <i class="bi bi-list"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="number" min="1" class="form-control form-control-xl" id="Number" required name="Number" placeholder="Number" >
                            <div class="form-control-icon">
                                <i class="bi bi-hash"></i>
                            </div>
                        </div>

                        <div class="progress position-relative" id="progressBar" style="display: none">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            <p class="progress-bar-text justify-content-center d-flex position-absolute w-100 text-white font-bold">0%</p>
                        </div>
                        <input type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2" value="Search">
                    </form>
                </div>

                <p class="text-center">Sentiment Analysis © 2023. All Rights Reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myForm").submit(function(event) {
                event.preventDefault();

                var formData = {
                    Keyword: $("#Keyword").val(),
                    Number: $("#Number").val()
                };

                $.ajax({
                    url: "index.php",
                    method: "POST",
                    data: formData,
                    beforeSend: function() {
                        $("#progressBar").show();
                        $(".progress-bar").animate({width: "100%"}, {
                            duration: 5000,
                            step: function(e) {
                                $(".progress-bar-text").text(Math.ceil(e) + "%");
                            }
                        }); 
                    },
                    success: function(e) {
                        $(".progress-bar").css({width: "0%"});
                        $(".progress-bar-text").text("0%");
                        $("#Keyword").val('');
                        $("#Number").val('');
                    }
                })
            })
        })
    </script>
</body>
</html>
