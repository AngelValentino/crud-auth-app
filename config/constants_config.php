<?php

if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/crud-auth-app');
    define('BASE_URL', '/crud-auth-app');
} 
else {
    define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST']);
}