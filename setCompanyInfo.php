<?php

// definition of variables 
$name = ''; 
$address = '';
$email = '';
$phone = '';
$website = '';
$logo = '';
$dirForUpload = 'img/uploads/';

// validation function 
function validateInput($data) 
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}//validateInput()

// obtain the data from POST
if($_POST) 
{
    $name = validateInput($_POST['company_name']);
    $address = validateInput($_POST['company_add']);
    $email = validateInput($_POST['company_email']);
    $phone = validateInput($_POST['company_phone']);
    $website = validateInput($_POST['company_website']);

    // handling the image
    $targetFile = $dirForUpload . basename($_FILES['logo']['name']);
    $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES['logo']['tmp_name']);

    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
        throw new Exception('Error while uploading image',1);
    }
} else {
    throw new Exception("Error Processing Request", 1);
}

$company = array(
        'name' => $name,
        'address' => $address,
        'email' => $email,
        'phone' => $phone,
        'website' => $website,
        'logo' => $targetFile
    );

//var_dump($company); die();
// saving the data into a file
file_put_contents('data.json',json_encode($company));

// redirecting to index
header('Location: ' . $_SERVER['SERVER_NAME']);

// to decode have to use
// json_decode(file_get_contents('data.json'),true);