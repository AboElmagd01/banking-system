<?php
include 'compareImages.php';
function upload($data)
{
    $target_dir = "/uploads/";
    $target_file = $target_dir . basename($data["fingerprint"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($data["fingerprint"]["tmp_name"]);
        if ($check !== false) {
//            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $_SESSION['errorMsg']= "File is not an image.";
            $uploadOk = 0;
            return false;
        }
    }

// Check if file already exists
//    if (file_exists($target_file)) {
//        $_SESSION['errorMsg']= "Sorry, file already exists.";
//        $uploadOk = 0;
//    }

// Check file size
    if ($data["fingerprint"]["size"] > 5000000) {
        $_SESSION['errorMsg']= "Sorry, your file is too large.";
        $uploadOk = 0;
        return false;
    }

// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $_SESSION['errorMsg']= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        return false;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['errorMsg']= "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($data["fingerprint"]["tmp_name"], __DIR__ . $target_file)) {
            $_SESSION['errorMsg']= "The file " . htmlspecialchars(basename($data["fingerprint"]["name"])) . " has been uploaded.";
//            echo ' <div class="alert alert-success alert-dismissible text-white" role="alert">
//                <span class="text-sm">'. $msg .'</span>
//                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
//                  <span aria-hidden="true">&times;</span>
//                </button>
//              </div>';
        } else {
            session_start();
            $_SESSION['errorMsg']= "Sorry, there was an error uploading your file.";
            return false;
        }
    }
    $compareMachine = new compareImages(__DIR__ . $target_dir . $data['fingerprint']['name']);
    $image1 = __DIR__ . $target_dir . $data['fingerprint']['name'];
    $image1Hash = $compareMachine->getHasString();
    return $image1Hash;
}
