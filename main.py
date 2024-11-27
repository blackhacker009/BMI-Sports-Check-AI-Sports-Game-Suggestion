from flask import Flask, render_template, request
import csv

app = Flask(__name__)

# Function to read CSV and return the data
def read_csv(filename):
    data = []
    with open(filename, newline='', encoding='utf-8') as file:
        reader = csv.reader(file)
        for row in reader:
            data.append(row)
    return data

# Function to check the user's input against the CSV
def check_bmi(gender, height, weight):
    # Path to your CSV file
    filename = 'bmisports.csv'
    data = read_csv(filename)

    # Search for a match
    for row in data:
        if row[0] == gender and int(row[1]) == height and int(row[2]) == weight:
            return f"Match found: Gender is {row[0]}, Height is {row[1]} cm, Weight is {row[2]} kg, Game: {row[4]}"
    return "No match found."

@app.route('/', methods=['GET', 'POST'])
def index():
    result = None
    is_match = False

    # Check if the form was submitted
    if request.method == 'POST':
        gender = request.form['gender']
        height = int(request.form['height'])
        weight = int(request.form['weight'])
        
        # Get the result from the CSV check
        result = check_bmi(gender, height, weight)
        is_match = "Match found" in result  # Check if it's a match

    return render_template('index.html', result=result, is_match=is_match)

if __name__ == '__main__':
    app.run(debug=True)
