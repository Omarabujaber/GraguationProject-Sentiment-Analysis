import tweepy
import numpy as np
from keras.models import load_model
import pickle
import nltk
from nltk import pos_tag
from keras.preprocessing.sequence import pad_sequences
from sklearn.feature_extraction.text import CountVectorizer
import mysql.connector
import requests
import webbrowser
import sys

# Setting up Twitter API:
CONSUMER_KEY = '91h2eFy4gSKnBhwSavlTyqRMU'
CONSUMER_SECRET = 'eZxIdDWAekliXQHH9G1zqhxI2nBPmneLTZ76ekubxq86CsSxaU'
BEARER_TOKEN = 'AAAAAAAAAAAAAAAAAAAAAFFBpgEAAAAA3j3rpe9Gr6na6QlNZuDxIQqoPPM%3DGo7fylxrgZMdVQjC5rE8ooJW1SeKFefOArvUGh2JGAOqcCteQG'
ACCESS_TOKEN = '3327539140-Fj1iytcr8b7BA9TYsG9jI0mIFxw16OyIlBbhjiW'
ACCESS_TOKEN_SECRET = 'Rn2WAqE6FVnoulgHkuWcaLdnarnFz7XkGt7nbfXtzCmfM'

api = tweepy.Client(BEARER_TOKEN)

def fetch_tweets(Keyword, Number):
    tweets = []
    
    queryBuilder = Keyword + ' lang:en -is:retweet'

    for tweet in tweepy.Paginator(api.search_recent_tweets, query=queryBuilder, max_results=Number).flatten(limit=Number): 
        tweets.append(tweet.text)
    return tweets


# Load the model and utilities
model = load_model('D:\\xamppp\\htdocs\\SAFPR\\sentimentModel.h5')

with open('D:\\xamppp\\htdocs\\SAFPR\\tokenizer.pkl', 'rb') as handle:
    tokenizer = pickle.load(handle)
with open('D:\\xamppp\\htdocs\\SAFPR\\bow_vectorizer.pkl', 'rb') as handle:
    vectorizer = pickle.load(handle)
with open('D:\\xamppp\\htdocs\\SAFPR\\pos_vectorizer.pkl', 'rb') as handle:
    pos_vectorizer = pickle.load(handle)
with open('D:\\xamppp\\htdocs\\SAFPR\\scaler.pkl', 'rb') as handle:
    scaler = pickle.load(handle)

def preprocess_tweets(tweets, tokenizer, vectorizer, pos_vectorizer, scaler):
    # Tokenize and pad
    X_seq = tokenizer.texts_to_sequences(tweets)
    X_seq = pad_sequences(X_seq, maxlen=2118)
    
    # Bag of Words feature
    X_bow = vectorizer.transform(tweets).toarray()

    # Part-of-speech feature
    texts_pos = [' '.join([pos for word, pos in pos_tag(text.split())]) for text in tweets]
    X_pos = pos_vectorizer.transform(texts_pos).toarray()

    # Combine and normalize
    X_combined = np.hstack((X_bow, X_pos))
    X_combined = scaler.transform(X_combined)
    
    return X_seq, X_combined

def predict_sentiment(inputs):
    predictions = model.predict(inputs)
    return np.argmax(predictions, axis=1)

def sentiment_to_star(sentiments):
    star_mapping = {0: 1, 1: 3, 2: 5}
    stars = [star_mapping[s] for s in sentiments]
    return stars


db_connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='safpr_summer_2023'
)
db_cursor = db_connection.cursor()


delete_query = "DELETE FROM tweets"
db_cursor.execute(delete_query)


def display_results(tweets, sentiments):
    sentiment_mapping = {0: "Negative", 1: "Neutral", 2: "Positive"}
    for tweet, sentiment in zip(tweets, sentiments):
        #print(f"Tweet: {tweet}\nSentiment: {sentiment_mapping[sentiment]}\n")

        insert_query = "INSERT INTO tweets (Text, Sentiment) VALUES (%s, %s)"
        db_cursor.execute(insert_query, (tweet, sentiment_mapping[sentiment]))
        db_connection.commit()




# Fetch tweets
#Keyword = input("Enter Your Search Term: ")
#Number = int(input("Enter Number Of Tweets: "))

Keyword = sys.argv[1]
Number = int(sys.argv[2])

tweets = fetch_tweets(Keyword,Number)

#if not tweets:
#    print(f"No tweets found for the search term '{Keyword}'.")
#    exit()


X_seq, X_combined = preprocess_tweets(tweets, tokenizer, vectorizer, pos_vectorizer, scaler)

# Predict
sentiments = predict_sentiment([X_seq, X_combined])
display_results(tweets, sentiments)

# Convert to Star Ratings and Display
star_ratings = sentiment_to_star(sentiments)
average_rating = np.mean(star_ratings)
print(f"\nAverage Star Rating for '{Keyword}': {average_rating:.2f} stars")


Number2 = str(Number)
Average = str(average_rating)


# URL of the PHP file
php_url = "http://localhost/SAFPR/View.php?Keyword=" + Keyword + "&Number=" + Number2 + "&Average=" + Average

webbrowser.open(php_url)































