HRassess
HRassess is an AI-powered system designed to provide personalized insights into users' personality traits based on the results of a personality test. The system also suggests career paths aligned with users' personality traits. HRassess operates under the premise that the user is interacting with an expert psychologist specializing in personality traits and the Big Five model.

User Takes the Test
User Registration: The user logs in to the HRassess website and completes the registration process.

Personality Test: After registration, the user takes the personality test available on the HRassess platform.

Processing in PHP
Submission Trigger: When the user submits the personality test, the PHP script (process_test.php) is automatically triggered.

Dynamic Generation of User Information: The PHP script dynamically generates user-specific information, such as the user directory, based on the user's session or stored data.

Execution of Python Script: The PHP script executes the Python script using the exec function, passing the user directory as a command-line argument.

Python Script Execution
Data Processing: The Python script receives the user directory as input and processes the user-specific data.

Analysis: The script performs analysis on the personality test results and generates insights into the user's personality traits.

Career Path Recommendations: Based on the personality analysis, the script suggests career paths that align with the user's personality.

Emailing the Result
Result Generation: After executing the Python script, the PHP script retrieves the analysis result.

Email Notification: Using the mail function, the PHP script sends the analysis result to the user's email address.

Automatic Process
The entire process, from user registration to receiving the analysis result via email, happens automatically without manual intervention. Users can log in, take the personality test, and receive their results at any time.

Setup
Clone the Repository: Clone the HRassess repository to your local machine.

bash
Copy code
git clone https://github.com/your_username/HRassess.git
Install Dependencies: Navigate to the project directory and install the required dependencies.

bash
Copy code
cd HRassess
pip install -r requirements.txt
Set Environment Variables: Set the required environment variables in a .env file. Include your OpenAI API key as OPENAI_API_KEY.

plaintext
Copy code
OPENAI_API_KEY=your_openai_api_key
Configure Document Directory: Place the personality test context documents in a directory accessible by the system. Update the document directory path in the script to point to the location of your personality test documents.

python
Copy code
loader = DirectoryLoader('/path/to/personality/test/documents', glob="*.pdf", loader_cls=PyPDFLoader)
Usage
Run the Script: Execute the script to analyze user questions and provide personalized insights.

bash
Copy code
python main.py
Input User Questions: Enter user questions when prompted. Supported questions include inquiries about personality traits and career paths.

View Results: HRassess will process the questions and display the corresponding answers based on the provided context documents and expert knowledge.

Contributions
Contributions to HRassess are welcome! If you have ideas for improvements or new features, feel free to open an issue or submit a pull request.

License
This project is licensed under the MIT License.

