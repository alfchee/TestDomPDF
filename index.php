<?php

// check if the file of the data exists
if(file_exists('data.json')) 
{
    // obtain the info of the company
    $company = json_decode(file_get_contents('data.json'),true);
} else {
    $company =  null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instacolin | PDF Generator</title>

     <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Begin of menu -->
    <nav class="navbar navbar-inverse navbar fixed top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand">Instacolin</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">PDF</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End of menu -->

    <!-- Begin of content -->
    <div class="container">
        <div class="row">
            <div class="company-information col-md-5 col-md-offset-3">
                <h1>Enter Company Information</h1>
                <p>Please enter your Business Company Information to be able to use it as (SHORTCODE).</p>
                <form action="setCompanyInfo.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input name="company_name" id="company_name" class="form-control" placeholder="Company Name" value="<?php echo $company ? $company['name'] : '' ?>" />
                    </div>
                    <div class="form-group">
                        <input name="company_add" id="company_add" class="form-control" placeholder="Company Address" value="<?php echo $company ? $company['address'] : '' ?>" />
                    </div>
                    <div class="form-group">
                        <input name="company_email" id="company_email" class="form-control" placeholder="Company Email" value="<?php echo $company ? $company['email'] : '' ?>" />
                    </div>
                    <div class="form-group">
                        <input name="company_phone" id="company_phone" class="form-control" placeholder="Company Phone Number" value="<?php echo $company ? $company['phone'] : '' ?>" />
                    </div>
                    <div class="form-group">
                        <input name="company_website" id="company_website" class="form-control" placeholder="Company Website" value="<?php echo $company ? $company['website'] : '' ?>" />
                    </div>
                    <div class="form-group">
                        <label for="image">Company Logo</label>
                        <input type="file" value="Company Logo" id="image" name="logo" />
                    </div>

                    <button type="submit" class="btn btn-primary">Save Details</button>
                </form>
            </div>
        </div>
        <div class="row" style="margin-bottom: 60px;">
            <div class="col-md-12 text-center">
                <a href="printPDF.php" class="btn btn-lg btn-info">PDF Report</a>
            </div>
        </div>
        
    </div>


    <!-- Vendor Scripts -->
    <script type="text/javascript" src="js/jquery.min.js" ></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>