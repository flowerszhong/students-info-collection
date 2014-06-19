<?php

$to = "flowerszhong@hotmail.com";
$subject = "Test mail";
$message = "Hello! This is a simple email message.";
$from = "srxhzyh@163.com";
$headers = "From: $from";
mail($to,$subject,$message,$headers);
echo "Mail Sent.";

?>