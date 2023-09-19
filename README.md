# Sentiment-Analysis
Sentiment Analysis with Twitter Data

Our project harnesses the power of machine learning to analyze sentiments from Twitter data. Leveraging a dataset of 67,000 tweets, categorized into positive, negative, and neutral sentiments, the system utilizes a combination of word embeddings, Bag of Words, and part-of-speech tagging to derive features for a deep learning model. The aim is to provide insightful sentiment scores that are more reflective of human intuition.

This code is a Python and PHP-based project for performing sentiment analysis on Twitter data. It includes two main components: data collection using the Twitter API and the analysis and display of sentiment results through a web interface.

Twitter Data Collection (Python)

Dependencies

tweepy: A Python library for accessing the Twitter API.
numpy: Used for numerical operations.
keras: Deep learning framework for loading a sentiment analysis model.
pickle: For loading various preprocessed data and models.
nltk: Natural Language Toolkit for text preprocessing.
mysql.connector: Connector for MySQL database.
requests: Used for making HTTP requests.
webbrowser: For opening a web browser from Python.
sys: For command-line arguments.
Functionality

The code sets up Twitter API credentials (consumer key, consumer secret, access token, etc.) to fetch tweets based on a user-specified keyword and the number of tweets to retrieve.

It loads a pre-trained sentiment analysis model (sentimentModel.h5) along with several preprocessed data objects (tokenizers, vectorizers, scalers) to prepare the tweets for sentiment analysis.

The collected tweets are preprocessed, including tokenization, bag of words (BoW) feature extraction, and part-of-speech tagging. These processed features are then normalized.

Sentiment analysis is performed using the loaded deep learning model, and the results are converted into star ratings (1 to 5 stars) based on the sentiment polarity.

The results are stored in a MySQL database, and the average star rating is computed for the collected tweets.

Finally, a local PHP file (View.php) is opened in a web browser to display the sentiment analysis results.

Web Interface (PHP and HTML)

Dependencies

HTML/CSS: For creating a user-friendly web interface.
Bootstrap: For styling the web pages.
simple-datatables: A library for displaying data in tabular form.
JavaScript: For handling user interactions and AJAX requests.
Functionality

The PHP file (View.php) receives keyword, number of tweets, and average rating as parameters from the Python script.

It dynamically generates an HTML page with background images related to the keyword and displays the sentiment analysis results, including the average star rating and a table of tweets with their corresponding sentiments.

The star rating is displayed using Bootstrap icons, with filled stars indicating positive sentiments and empty stars for negative sentiments.

The table of tweets is created using the simple-datatables library, allowing for easy sorting and searching of the tweet data.

JavaScript is used to handle user interactions, such as submitting a new keyword and number of tweets for analysis. An AJAX request is made to the Python script (index.php) to initiate the analysis process.

A progress bar is displayed during the analysis, and the results are updated in real-time when the analysis is complete.

The web interface provides an interactive and visually appealing way to explore sentiment analysis results.

Overall, this code showcases the process of collecting Twitter data, performing sentiment analysis, and presenting the results through a web interface.
