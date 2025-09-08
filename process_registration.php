<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $firstName = test_input($_POST["first_name"]);
    $lastName = test_input($_POST["last_name"]);
    $email = test_input($_POST["email"]);
    $dateOfBirth = test_input($_POST["date_of_birth"]);
    $occupation = test_input($_POST["occupation"]);
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Add more fields as needed

    // Create a directory for the user using their first name
    $userDirectory = "user_data/$firstName";

    // Check if the directory already exists
    if (!file_exists($userDirectory)) {
        // Create the directory with more restrictive permissions (0755 may be too permissive)
        mkdir($userDirectory, 0700, true);

        // Save user data to a txt file in the user's directory
        $userData = "First Name: $firstName\n";
        $userData .= "Last Name: $lastName\n";
        $userData .= "Email: $email\n";
        $userData .= "Date of Birth: $dateOfBirth\n";
        $userData .= "Occupation: $occupation\n";
        $userData .= "Username: $username\n";
        $userData .= "Password: $hashedPassword\n";
        // Add more user data fields as needed

        $fileName = "$userDirectory/$firstName.txt";
        file_put_contents($fileName, $userData);

        // Redirect to the personality test page
        header("Location: personality_test_page.html");
        exit();
    } else {
        echo "Error: User directory already exists.";
    }
}

// Function to sanitize user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
