import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.preprocessing import StandardScaler
from keras.preprocessing.text import Tokenizer
from keras.preprocessing.sequence import pad_sequences
from keras.models import Sequential, Model
from keras.layers import Dense, Embedding, LSTM, SpatialDropout1D, concatenate, Input
from keras.utils import to_categorical
import pickle
import nltk
from nltk import pos_tag
from nltk.corpus import stopwords
nltk.download('averaged_perceptron_tagger')


df = pd.read_csv('Tweets.csv')
texts = df['text'].values
sentiments = df['sentiment'].values

# Convert sentiments to integer labels
label_mapping = {"negative": 0, "neutral": 1, "positive": 2}
labels = np.array([label_mapping[sentiment] for sentiment in sentiments])

# Tokenization and sequence padding for GloVe
max_features = 5000
tokenizer = Tokenizer(num_words=max_features, split=' ')
tokenizer.fit_on_texts(texts)
X_seq = tokenizer.texts_to_sequences(texts)
X_seq = pad_sequences(X_seq)

# Load GloVe embeddings
embeddings_index = {}
with open('glove.6B.100d.txt', encoding='utf8') as f:
    for line in f:
        values = line.split()
        word = values[0]
        coefs = np.asarray(values[1:], dtype='float32')
        embeddings_index[word] = coefs

# Create embedding matrix
embedding_dim = 100
embedding_matrix = np.zeros((max_features, embedding_dim))
for word, i in tokenizer.word_index.items():
    if i < max_features:
        embedding_vector = embeddings_index.get(word)
        if embedding_vector is not None:
            embedding_matrix[i] = embedding_vector

# Bag of Words feature
vectorizer = CountVectorizer(max_features=5000)
X_bow = vectorizer.fit_transform(texts).toarray()

# Part-of-speech feature
texts_pos = [' '.join([pos for word, pos in pos_tag(text.split())]) for text in texts]
pos_vectorizer = CountVectorizer(max_features=5000)
X_pos = pos_vectorizer.fit_transform(texts_pos).toarray()

# Combine all features into a single array
X_combined = np.hstack((X_bow, X_pos))

# Normalize combined features
scaler = StandardScaler()
X_combined = scaler.fit_transform(X_combined)

# Splitting data
labels_categorical = to_categorical(labels, num_classes=3)
X_seq_train, X_seq_test, X_combined_train, X_combined_test, y_train, y_test = train_test_split(X_seq, X_combined,
                                                                                               labels_categorical,
                                                                                               test_size=0.2,
                                                                                               random_state=42)

# Model with multiple inputs
input_text = Input(shape=(X_seq.shape[1],))
x = Embedding(max_features, embedding_dim, weights=[embedding_matrix], trainable=False)(input_text)
x = SpatialDropout1D(0.2)(x)
x = LSTM(100, dropout=0.2, recurrent_dropout=0.2)(x)

input_combined = Input(shape=(X_combined.shape[1],))
concatenated = concatenate([x, input_combined])

out = Dense(3, activation='softmax')(concatenated)

model = Model(inputs=[input_text, input_combined], outputs=out)
model.compile(loss='categorical_crossentropy', optimizer='adam', metrics=['accuracy'])
model.summary()

model.fit([X_seq_train, X_combined_train], y_train, epochs=5, batch_size=32, validation_split=0.2)

# Evaluate
accuracy = model.evaluate([X_seq_test, X_combined_test], y_test)[1] * 100
print(f"Model Accuracy: {accuracy:.2f}%")

model.save("sentiment_model_with_glove_and_features.h5")
with open('tokenizer.pkl', 'wb') as handle:
    pickle.dump(tokenizer, handle, protocol=pickle.HIGHEST_PROTOCOL)
with open('bow_vectorizer.pkl', 'wb') as handle:
    pickle.dump(vectorizer, handle, protocol=pickle.HIGHEST_PROTOCOL)
with open('pos_vectorizer.pkl', 'wb') as handle:
    pickle.dump(pos_vectorizer, handle, protocol=pickle.HIGHEST_PROTOCOL)
with open('scaler.pkl', 'wb') as handle:
    pickle.dump(scaler, handle, protocol=pickle.HIGHEST_PROTOCOL)
