import pandas as pd
import sys
import json
from sklearn.preprocessing import LabelEncoder
from sklearn.ensemble import RandomForestClassifier
from datetime import datetime

# Load dataset
data = pd.read_csv('obesity_dataset_with_diet.csv')

# Encode categorical features
obesity_encoder = LabelEncoder()
diet_encoder = LabelEncoder()

data['Obesity_Level'] = obesity_encoder.fit_transform(data['Obesity_Level'])
data['Diet'] = diet_encoder.fit_transform(data['Diet'])

# Prepare training data
X = data[['Age', 'Height (cm)', 'Weight (kg)', 'BMI', 'Diet']]
y = data['Obesity_Level']

# Train model
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X, y)

# Read JSON input from PHP
try:
    input_data = json.loads(sys.stdin.read())
except json.JSONDecodeError:
    print(json.dumps({"risk": "Invalid input data", "tips": ""}))
    sys.exit()

# Calculate Age from Date of Birth
try:
    dob_str = input_data['DateofBirth']
    dob = datetime.strptime(dob_str, "%Y-%m-%d")
    today = datetime.today()
    age = today.year - dob.year - ((today.month, today.day) < (dob.month, dob.day))
except Exception:
    print(json.dumps({"risk": "Invalid Date of Birth format", "tips": ""}))
    sys.exit()

# Encode diet type
try:
    diet_encoded = diet_encoder.transform([input_data['Diet']])[0]
except ValueError:
    print(json.dumps({"risk": "Diet type not recognized", "tips": ""}))
    sys.exit()

# Build input dataframe
input_df = pd.DataFrame([{
    'Age': age,
    'Height (cm)': input_data['Height'],
    'Weight (kg)': input_data['Weight'],
    'BMI': input_data['BMI'],
    'Diet': diet_encoded
}])

# Risk messages and tips
health_risks = {
    'Underweight': "There is an increased risk of nutritional deficiencies, weak immunity, and fatigue.",
    'Overweight': "There is a higher risk of heart disease, joint problems, and high blood pressure.",
    'Obese': "There is significant risk of diabetes, cardiovascular diseases, and respiratory issues.",
    'Extremely Obese': "There is a severe risk of chronic diseases, mobility issues, and reduced life expectancy."
}

health_tips = {
    'Underweight': "Include calorie-dense foods, perform strength training, and eat regular balanced meals.",
    'Overweight': "Adopt a balanced diet, stay active, and monitor your portion sizes.",
    'Obese': "Consult a nutritionist, increase physical activity, and follow a structured weight management plan.",
    'Extremely Obese': "Seek medical help, follow a supervised program, and change your lifestyle as needed."
}

# Predict
predicted = model.predict(input_df)
label = obesity_encoder.inverse_transform(predicted)[0]

# Result
result = {
    "risk": health_risks.get(label, ""),
    "tips": health_tips.get(label, "")
}

print(json.dumps(result))
