<?php

header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

$con = mysqli_connect('srv1113.hstgr.io', 'u858168866_usrservices', 'oGnRV2Yh*Z4', 'u858168866_services', 3306) or die("Could not connect to database");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tag']) && $_POST['tag'] != '') {
        $tag = $_POST['tag'];
        $response = array();

        // 🔹 REGISTER
        if ($tag == "register") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = md5($_POST['password']);

            $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            $result = mysqli_query($con, $query);

            if ($result) {
                $id = mysqli_insert_id($con);
                $response["error"] = 0;
                $response["message"] = "Registered successfully!";
                $response["id"] = $id;
            } else {
                $response["error"] = 1;
                $response["message"] = "Email already registered!";
            }
            echo json_encode($response);
        }

        // 🔹 LOGIN
        if ($tag == "login") {
            $email = $_POST['email'];
            $password = md5($_POST['password']);

            $query = "SELECT id, name FROM users WHERE email='$email' AND BINARY password='$password'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
              
        $response = [
                "error" => 0,
                "message" => "Login successful!",
                "id" => $row['id'],
                "name" => $row['name']
            ];
            } else {
                $response["error"] = 1;
                $response["message"] = "Invalid credentials!";
            }
            echo json_encode($response);
        }

        // 🔹 ADD SUBSCRIPTION
        if ($tag == "add_subscription") {
            $user_id = $_POST['user_id'];
            $name = $_POST['name'];
            $category = $_POST['category'];
            $amount = $_POST['amount'];
            $frequency = $_POST['frequency'];
            $start_date = $_POST['start_date'];
            $due_date = $_POST['due_date'];

            $query = "INSERT INTO subscriptions (user_id, name, category, amount, frequency, start_date, due_date) 
                      VALUES ('$user_id', '$name', '$category', '$amount', '$frequency', '$start_date', '$due_date')";
            $result = mysqli_query($con, $query);

            if ($result) {
                $response["error"] = 0;
                $response["message"] = "Subscription added successfully!";
            } else {
                $response["error"] = 1;
                $response["message"] = "Failed to add subscription.";
            }
            echo json_encode($response);
        }

        // 🔹 GET SUBSCRIPTIONS
        if ($tag == "get_subscriptions") {
            $user_id = $_POST['user_id'];
            $query = "SELECT * FROM subscriptions WHERE user_id = '$user_id' ORDER BY due_date ASC";
            $result = mysqli_query($con, $query);

            $subscriptions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $subscriptions[] = $row;
            }

            $response["error"] = 0;
            $response["subscriptions"] = $subscriptions;
            echo json_encode($response);
        }

        // 🔹 UPDATE SUBSCRIPTION
        if ($tag == "update_subscription") {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $category = $_POST['category'];
            $amount = $_POST['amount'];
            $frequency = $_POST['frequency'];
            $start_date = $_POST['start_date'];
            $due_date = $_POST['due_date'];

            $query = "UPDATE subscriptions SET name='$name', category='$category', amount='$amount', frequency='$frequency', start_date='$start_date', due_date='$due_date' WHERE id='$id'";
            $result = mysqli_query($con, $query);

            if ($result) {
                $response["error"] = 0;
                $response["message"] = "Subscription updated successfully!";
            } else {
                $response["error"] = 1;
                $response["message"] = "Failed to update.";
            }
            echo json_encode($response);
        }

        // 🔹 DELETE SUBSCRIPTION
        if ($tag == "delete_subscription") {
            $id = $_POST['id'];
            $query = "DELETE FROM subscriptions WHERE id = '$id'";
            $result = mysqli_query($con, $query);
            if ($result) {
                $response["error"] = 0;
                $response["message"] = "Subscription deleted successfully!";
                } else {
                    $response["error"] = 1;
                    $response["message"] = "Failed to delete.";
                    }
                    echo json_encode($response);
        }

        // 🔹 MARK AS PAID (optional if you add a `status` column)
        if ($tag == "mark_as_paid") {
            $id = $_POST['id'];
            $next_due = $_POST['next_due']; 

            $query = "UPDATE subscriptions SET due_date = '$next_due' WHERE id='$id'";
            $result = mysqli_query($con, $query);

            if ($result) {
                $response["error"] = 0;
                $response["message"] = "Marked as paid.";
            } else {
                $response["error"] = 1;
                $response["message"] = "Failed to update.";
            }
            echo json_encode($response);
        }
        
    } else {
        $response["error"] = 1;
        $response["error_msg"] = "Required parameter 'tag' is missing!";
        echo json_encode($response);
    }
} else {
    echo 'Bill Tracker';
}
