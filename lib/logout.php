<?php
session_start();
session_destroy();

echo("<script> alert('Logged Out!'); location.href='../index.php';</script>");