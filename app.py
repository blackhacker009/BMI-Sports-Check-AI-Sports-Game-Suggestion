from flask import Flask, render_template, request
import joblib
import pandas as pd

app = Flask(__name__)

# Load the trained model and encoders
model = joblib.load("sport_predictor_model.pkl")
gender_encoder = joblib.load("gender_encoder.pkl")
sport_encoder = joblib.load("sport_encoder.pkl")

# Function to predict the sport
def predict_sport(gender, height, weight, age):
    # Encode gender
    gender_encoded = gender_encoder.transform([gender])[0]
    # Prepare input data
    input_data = pd.DataFrame([[gender_encoded, height, weight, age]], columns=['Gender', 'Height', 'Weight', 'Age'])
    # Predict sport
    sport_encoded = model.predict(input_data)[0]
    # Decode the sport
    return sport_encoder.inverse_transform([sport_encoded])[0]

@app.route('/', methods=['GET', 'POST'])
def index():
    result = None

    if request.method == 'POST':
        # Get user input
        gender = request.form['gender']
        height = int(request.form['height'])
        weight = int(request.form['weight'])
        age = int(request.form['age'])

        # Predict sport
        result = predict_sport(gender, height, weight, age)

    return render_template('index.html', result=result)

if __name__ == '__main__':
    app.run(debug=True)
