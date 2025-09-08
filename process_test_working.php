<?php
// Function to sanitize user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $firstName = "";  // Initialize firstName

    // Check if the 'first_name' key exists in the $_POST array
    if (isset($_POST["first_name"])) {
        $firstName = test_input($_POST["first_name"]);

        // Retrieve question text and selected answers
        for ($i = 1; $i <= 12; $i++) {
            // Check if the hidden field for the question text exists
            if (isset($_POST["q" . $i . "_text"])) {
                $questionText = test_input($_POST["q" . $i . "_text"]);
                $answer = test_input($_POST["q" . $i]);

                // Save question text and selected answers in test_data.txt
                $userDirectory = "user_data/$firstName";
                file_put_contents("$userDirectory/test_data.txt", "$i. $questionText\n($answer)\n\n", FILE_APPEND);
            }
        }

        // Redirect to thank you page
        header("Location: thank_you.html");
        exit();
    } else {
        echo "Error: 'first_name' key not found in the form data.";
    }
} else {
    // Redirect to the next page of the test (if needed)
    // header("Location: next_test_page.html");
    // exit();
}
?>
