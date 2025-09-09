<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = getenv('SMTP_HOST');
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('SMTP_USERNAME');
                $mail->Password   = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom(getenv('SMTP_USERNAME'), 'InfraBuild AI');
                $mail->addAddress($email, $firstName);     // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Personality Test Analysis Result';
                $mail->Body    = "<pre>" . htmlspecialchars($output) . "</pre>";
                $mail->AltBody = $output;

                $mail->send();
            } catch (Exception $e) {
                // Don't die, but maybe log the error
                // For now, we'll just ignore it and redirect as usual
            }
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
