<?php

$hobbies = 
$data=$_POST;
echo $data['fullname'];
echo '<br>';
echo $data['email'];
echo '<br>';
echo $data['password'];
echo '<br>';
echo $data['ConfirmPassword'];
echo '<br>';
echo $data['Gender'];
echo '<br>';
$Hobbies=$_POST['Hobbies'];
foreach($Hobbies as $item)
{
    echo $item." ";
}
echo '<br>';
echo $data['Country'];
?>