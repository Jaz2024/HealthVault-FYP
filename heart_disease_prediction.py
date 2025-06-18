import sys
import json
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.ensemble import RandomForestClassifier

# Training data
data = {
    "ID": list(range(1, 51)),
    "Blood Pressure": [
        "124/79", "153/77", "119/70", "152/67", "152/88", "135/92", "145/62", "148/89", "106/61", "113/97",
        "115/85", "126/71", "115/94", "137/97", "123/79", "125/63", "108/88", "158/90", "116/83", "130/92",
        "125/87", "122/88", "141/74", "130/100", "115/88", "153/60", "117/86", "141/77", "112/65", "127/93",
        "111/88", "159/76", "103/66", "113/84", "151/98", "109/61", "153/96", "118/75", "123/72", "125/73",
        "104/87", "160/90", "131/62", "106/97", "107/98", "141/63", "117/72", "144/87", "115/62", "145/93"
    ],
    "Heart Rate": [
        "87", "64", "67", "80", "77", "69", "62", "108", "62", "95", "105", "77", "104", "82", "91", "80", "107",
        "68", "62", "84", "78", "62", "110", "110", "108", "92", "105", "108", "61", "63", "96", "90", "66", "60",
        "77", "104", "71", "110", "98", "83", "84", "80", "60", "103", "89", "88", "101", "90", "72", "83"
    ],
    "BMI": [
        26.5, 21.9, 21.5, 23.7, 38.9, 38.2, 27.3, 38.3, 35.2, 34.0, 32.4, 18.2, 27.1, 39.5, 22.9, 38.7, 26.7, 37.7,
        35.3, 37.5, 29.3, 37.8, 24.9, 24.3, 37.4, 36.2, 38.7, 31.3, 29.3, 34.2, 34.4, 20.3, 26.1, 31.3, 19.9, 36.9,
        38.4, 26.4, 32.7, 33.0, 20.1, 37.3, 32.7, 26.6, 21.9, 22.4, 36.7, 34.2, 28.0, 25.8
    ],
    "Predicted Risk": [
        "Normal", "High Risk", "Normal", "High Risk", "High Risk", "High Risk", "High Risk", "High Risk", 
        "High Risk", "High Risk", "High Risk", "Normal", "High Risk", "High Risk", "Moderate Risk", "High Risk", 
        "High Risk", "High Risk", "High Risk", "High Risk", "Moderate Risk", "High Risk", "High Risk", 
        "High Risk", "High Risk", "High Risk", "High Risk", "High Risk", "Moderate Risk", "High Risk", 
        "High Risk", "High Risk", "High Risk", "Normal", "High Risk", "High Risk", "High Risk", "High Risk", 
        "High Risk", "Moderate Risk", "High Risk", "High Risk", "High Risk", "High Risk", "High Risk", 
        "High Risk", "Moderate Risk", "High Risk", "High Risk", "High Risk"
    ]
}

# Prepare the dataset
df = pd.DataFrame(data)
df[['Systolic', 'Diastolic']] = df['Blood Pressure'].str.strip().str.split('/', expand=True).astype(float)
df['Heart Rate'] = df['Heart Rate'].astype(float)
df = df.drop(['ID', 'Blood Pressure'], axis=1)

le = LabelEncoder()
df['Predicted Risk'] = le.fit_transform(df['Predicted Risk'])

X = df.drop('Predicted Risk', axis=1)
y = df['Predicted Risk']

model = RandomForestClassifier()
model.fit(X, y)

# ---------- Read input from PHP via stdin ----------
input_json = sys.stdin.read()
user_input = json.loads(input_json)

# Extract and convert input values
systolic, diastolic = map(float, user_input['BloodPressure'].split('/'))
heart_rate = float(user_input['HeartRate'])
bmi = float(user_input['BMI'])

# Create DataFrame for prediction with matching column order
input_df = pd.DataFrame([{
    'BMI': bmi,
    'Heart Rate': heart_rate,
    'Systolic': systolic,
    'Diastolic': diastolic
}])[X.columns]  # Ensure column order matches training data

# Predict
prediction = model.predict(input_df)[0]
risk_label = le.inverse_transform([prediction])[0]

# Output result with messages
risk_messages = {
    "High Risk": {
        "risk": "Your health indicators show a high risk of heart disease. Factors contributing to this may include elevated blood pressure, high heart rate, and increased BMI, all of which significantly raise the likelihood of cardiovascular issues.",
        "tip": "Consult a cardiologist immediately. Adopt a heart-healthy lifestyle: reduce sodium and sugar intake, increase fiber consumption, exercise at least 30 minutes daily, and avoid tobacco and excessive alcohol. Monitor your blood pressure and heart rate regularly."
    },
    "Moderate Risk": {
        "risk": "You are showing signs that could lead to heart disease if not managed well. This includes slightly elevated BMI or borderline blood pressure readings. While not critical now, this situation warrants caution.",
        "tip": "Maintain a balanced diet with reduced processed foods. Include light to moderate physical activity in your daily routine. Stay hydrated, manage stress levels, and consider regular check-ups to monitor your cardiovascular health."
    }
}

if risk_label != "Normal":
    print(json.dumps({
        "Risk_Message": risk_messages[risk_label]["risk"],
        "Health_Tip": risk_messages[risk_label]["tip"]
    }))
