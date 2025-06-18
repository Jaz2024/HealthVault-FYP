import sys
import json
import pandas as pd
from datetime import datetime
from sklearn.preprocessing import LabelEncoder
from sklearn.ensemble import RandomForestClassifier

# Read JSON input from stdin
input_json = sys.stdin.read()
input_data = json.loads(input_json)

# Calculate age from Date of Birth
dob_str = input_data['DateofBirth']
dob = datetime.strptime(dob_str, "%Y-%m-%d")
today = datetime.today()
age = today.year - dob.year - ((today.month, today.day) < (dob.month, dob.day))

# Prepare input for prediction
input_data = {
    "Alcohol": input_data['Alcohol_Consumption'],
    "Tobacco": input_data['Tobacco_Use'],
    "Age": age
}

# Training Data
data = {
    'Alcohol': ['Weekly', 'Occasionally', 'Never consumed alcohol', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Weekly', 'Occasionally', 'Never consumed alcohol', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly', 'Never consumed alcohol', 'Never consumed alcohol', 'Weekly', 'Never consumed alcohol', 'Occasionally', 'Weekly'],
    'Tobacco': ['Daily', 'Occasionally', 'Never Smoked', 'Occasionally', 'Daily', 'Never Smoked', 'Daily', 'Occasionally', 'Never Smoked', 'Daily', 'Never Smoked', 'Occasionally', 'Never Smoked', 'Daily', 'Occasionally', 'Daily', 'Never Smoked', 'Occasionally', 'Daily', 'Occasionally', 'Never Smoked', 'Daily', 'Never Smoked', 'Daily', 'Occasionally', 'Never Smoked', 'Daily', 'Occasionally', 'Daily', 'Daily', 'Occasionally', 'Occasionally', 'Occasionally'],
    'Age': [25, 34, 45, 29, 50, 22, 40, 60, 35, 55, 18, 48, 30, 70, 39, 65, 28, 33, 42, 52, 27, 19, 38, 72, 41, 54, 66, 37, 31, 20, 46, 45, 68],
    'LiverRisk': ['High', 'Moderate', 'Low', 'Moderate', 'High', 'Low', 'High', 'Moderate', 'Low', 'High', 'Low', 'Moderate', 'Low', 'High', 'Moderate', 'High', 'Low', 'Moderate', 'High', 'Moderate', 'Low', 'Moderate', 'High', 'Low', 'High', 'Moderate', 'High', 'Low', 'Moderate', 'High', 'Moderate', 'Low', 'Moderate']
}

df = pd.DataFrame(data)

# Encode categorical columns
le_dict = {}
for col in ['Alcohol', 'Tobacco', 'LiverRisk']:
    le = LabelEncoder()
    df[col] = le.fit_transform(df[col])
    le_dict[col] = le

# Train the model
X = df.drop('LiverRisk', axis=1)
y = df['LiverRisk']
model = RandomForestClassifier()
model.fit(X, y)

# Convert input to DataFrame and encode
input_df = pd.DataFrame([input_data])
for col in ['Alcohol', 'Tobacco']:
    # Check if the label is unseen, handle it appropriately (e.g., map to a default value)
    if input_data[col] not in le_dict[col].classes_:
        input_df[col] = le_dict[col].transform(['Never consumed alcohol'])[0]  # Default or fallback value
    else:
        input_df[col] = le_dict[col].transform([input_data[col]])[0]
        
# Predict and decode label
prediction = model.predict(input_df)[0]
risk_label = le_dict['LiverRisk'].inverse_transform([prediction])[0]

# Output result with messages
risk_messages = {
    "High": {
        "risk": "There is a significant risk of developing liver diseases such as cirrhosis, fatty liver, or hepatitis due to high alcohol or tobacco consumption.",
        "tip": "It is strongly recommended to reduce or eliminate alcohol and tobacco use, follow a liver-friendly diet, and undergo regular medical checkups."
    },
    "Moderate": {
        "risk": "There is a moderate risk of liver strain which may lead to long-term liver issues if current habits continue unchecked.",
        "tip": "Limit alcohol and tobacco use, stay hydrated, maintain a balanced diet, and consider a liver function test as a precaution."
    }
}

if risk_label != "Low":
    print(json.dumps({
        "Risk_Message": risk_messages[risk_label]["risk"],
        "Health_Tip": risk_messages[risk_label]["tip"]
    }))
