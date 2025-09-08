import os
import sys
from dotenv import load_dotenv
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.vectorstores import Chroma
from langchain.document_loaders import DirectoryLoader, TextLoader
from langchain.chains import RetrievalQA


# Load environment variables from .env file
load_dotenv()

# Get Google AI API key from environment variable
google_api_key = os.getenv("GOOGLE_API_KEY")

# Check if the user directory is provided as a command-line argument
if len(sys.argv) < 2:
    print("Usage: python llm_process.py <user_directory>")
    sys.exit(1)

user_directory = sys.argv[1]

# Define the path to the user's test results
# The glob pattern is now more specific to how we save the test results file.
loader = DirectoryLoader(user_directory, glob="*_test_results.txt", loader_cls=TextLoader)
documents = loader.load()

if not documents:
    print(f"No test result files found in {user_directory}")
    sys.exit(1)

# Create the embeddings object for use with Google AI
# Note: We need to use an appropriate embedding model from Google AI
# For this example, we'll need to adjust if a specific embedding model is required.
# This part of the code might need to be updated depending on the langchain-google-genai library specifics.
from langchain_google_genai import GoogleGenerativeAIEmbeddings
embeddings = GoogleGenerativeAIEmbeddings(model="models/embedding-001")


# Embed and store the texts
# Using an in-memory vector store for simplicity, but can be persisted
vectordb = Chroma.from_documents(documents=documents, embedding=embeddings)

retriever = vectordb.as_retriever()

# Define user questions
user_questions = [
    "What are my personality traits based on the test results?",
    "What career paths align with my personality?",
    "Provide five bullet points that describe my strengths based on the test Q&A?",
    "Provide five bullet points that describe my potential challenges based on the test Q&A?",
    "How can I leverage these strengths and potential challenges in my career?",
    "Describe some of my most notable traits"
]

# Create the chain to answer user questions
llm = ChatGoogleGenerativeAI(model="gemini-flash", temperature=0.0)
qa_chain = RetrievalQA.from_chain_type(llm=llm, chain_type="stuff", retriever=retriever, return_source_documents=False)
qa_chain.combine_documents_chain.llm_chain.prompt.messages[0].prompt.template = '''
You are an expert Psychologist who specializes in personality traits and the Big Five model.
Analyze the following personality test results and provide a comprehensive analysis for the user.
{context}'''

# Process and display answers for all user questions
print("--- Personality Analysis Report ---")
for i, user_question in enumerate(user_questions, 1):
    response = qa_chain(user_question)
    user_answer = response['result']
    print(f"\n--- Question {i}: {user_question} ---\n")
    print(user_answer)
print("\n--- End of Report ---")
