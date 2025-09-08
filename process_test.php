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
    $firstName = test_input($_POST["first_name"]);

    // Define the user's directory
    $userDirectory = "user_data/$firstName";

    // Check if the user's directory exists
    if (file_exists($userDirectory) && is_dir($userDirectory)) {
        // Define the file path for the test results
        $fileName = "$userDirectory/{$firstName}_test_results.txt";
        $fileHandle = fopen($fileName, 'w');

        if ($fileHandle === false) {
            die("Error: Unable to create or open the results file.");
        }

        // Loop through each question and save the answer
        for ($i = 1; $i <= 47; $i++) {
            if (isset($_POST["q" . $i . "_text"]) && isset($_POST["q" . $i])) {
                $questionText = test_input($_POST["q" . $i . "_text"]);
                $answer = test_input($_POST["q" . $i]);
                $line = "$i. $questionText\n($answer)\n\n";
                if (fwrite($fileHandle, $line) === false) {
                    fclose($fileHandle);
                    die("Error: Unable to write to the results file.");
                }
            }
        }

        fclose($fileHandle);

        // --- Execute Python script for analysis ---
        $pythonExecutable = "python"; // Or specify the full path to your python executable
        $pythonScript = "llm_process.py"; // Relative path to the script
        $command = escapeshellcmd("$pythonExecutable $pythonScript " . escapeshellarg($userDirectory));
        $output = shell_exec($command);

        // --- Email the results to the user ---
        $email = '';
        $registrationFile = "$userDirectory/$firstName.txt";
        if (file_exists($registrationFile)) {
            $userData = file_get_contents($registrationFile);
            // Find the email address in the user data
            if (preg_match('/Email: (.*)/', $userData, $matches)) {
                $email = trim($matches[1]);
            }
        }

        if (!empty($email)) {
            $subject = "Your Personality Test Analysis Result";
            // Ensure the output is formatted nicely for an email
            $message = "<pre>" . htmlspecialchars($output) . "</pre>";
            $headers = "From: no-reply@infrabuild.ai\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            // Note: The mail() function requires a configured mail server (like sendmail or an SMTP server)
            // to be set up in your php.ini file. This may not work in a default XAMPP setup without configuration.
            mail($email, $subject, $message, $headers);
        }

        // Redirect to the thank you page
        header("Location: thank_you.html");
        exit();
    } else {
        // Handle the case where the user directory does not exist
        echo "Error: User directory not found. Please complete the registration first.";
    }
}
?>
