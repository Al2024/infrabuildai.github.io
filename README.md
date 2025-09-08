# HRassess

## Overview

HRassess is a system designed to assess users' personality traits based on a provided personality test. The system utilizes a combination of PHP and Python scripts to process the test data, perform analysis, and provide users with personalized insights.

## User Takes the Test

- Users log in to the HRassess website and take the personality test.
- The test consists of a series of questions designed to evaluate various aspects of the user's personality.

## Processing in PHP

- Upon test submission, the PHP script (`process_test.php`) is triggered automatically.
- This script processes the submitted test data and initiates the Python script for further analysis.

## Running Python Script

- The PHP script dynamically generates user-specific information, such as a directory for storing test results and analysis.
- It then invokes the Python script using the `exec` function, passing the user directory as a command-line argument.

## Python Script Execution

- The Python script receives the user-specific data and performs in-depth analysis of the personality traits.
- Using advanced algorithms and models, the script evaluates the test responses and identifies key personality characteristics.

## Emailing the Result

- Upon completion of the analysis, the PHP script utilizes the `mail` function to send the personalized analysis results to the user's email address.
- Users receive comprehensive insights into their personality traits and potential career paths that align with their characteristics.

## System Prompt

- Upon analysis completion, users receive an email with detailed insights into their personality traits.
- The system prompt provided to the Python script guides the analysis process, ensuring accurate assessment and meaningful results.

## System Renaming: HRassess

- The system, previously known as [Old Name], has been renamed to HRassess to better reflect its purpose and functionality.
- The new name aligns with the system's focus on human resources assessment and personality analysis.

## Automated Process

- The entire process, from test submission to result delivery, is fully automated and requires no manual intervention.
- Users can conveniently access their personalized analysis anytime, anywhere, through the HRassess platform.

This README provides an overview of the HRassess system, its components, and the automated process for assessing personality traits and providing personalized insights to users.


