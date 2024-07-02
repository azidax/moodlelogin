<?php
// Ensure Moodle configuration is loaded
require_once(__DIR__ . '/../../config.php');

// Function untuk tengok sama ada usernama wujud atau tak
function get_user_by_username($username) {
    global $DB;
    return $DB->get_record('user', array('username' => $username, 'deleted' => 0));
}

// function untuk dapatkan username dari HTTP GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // check kalau username dan token dari gotna sama baru process
    if (isset($_GET['username'])) {
        
            if (isset($_GET['course_after_login'] == $course_after_login)) {
                
                $username = $_GET['username'];

                if ($userauth = get_user_by_username($username)) {
                    // Authenticate the user
                    complete_user_login($userauth);

                    // Redirect to Moodle dashboard
                    redirect($course_after_login);
            } else {
                redirect($CFG->wwwroot . $course_after_login);
            }
        
        } else {
            echo "Username does not exist.";
        }
    } else {
        echo "Username parameter not provided.";
    }
} else {
    echo "404 - Invalid request method.";
}


?>
