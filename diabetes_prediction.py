import sys
import json
import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.ensemble import RandomForestClassifier

# Read input
input_data = json.load(sys.stdin)

# Define allowed values for validation
valid_values = {
    "Diet": ["Balanced", "High Sugar", "Unhealthy"],
    "Exercise": ["Daily", "Weekly", "Never"],
    "Tobacco_Use": ["Daily", "Occasionally", "Never Smoked"],
    "Alcohol_Consumption": ["Weekly", "Occasionally", "Never consumed alcohol"]
}

# Validate input values
for key in valid_values:
    if input_data[key] not in valid_values[key]:
        raise ValueError(f"Invalid value for {key}: '{input_data[key]}'")

# Load training data
data = pd.read_csv('diabetes_risk_dataset_v2.csv')
df = pd.DataFrame(data)

# Encode categorical columns
le_dict = {}
for col in ['Diet', 'Exercise', 'Tobacco_Use', 'Alcohol_Consumption', 'Diabetes_Risk']:
    le = LabelEncoder()
    df[col] = le.fit_transform(df[col])
    le_dict[col] = le

# Train the model
X = df.drop('Diabetes_Risk', axis=1)
y = df['Diabetes_Risk']
model = RandomForestClassifier()
model.fit(X, y)

# Prepare input for prediction
input_df = pd.DataFrame([input_data])
for col in ['Diet', 'Exercise', 'Tobacco_Use', 'Alcohol_Consumption']:
    le = le_dict[col]
    input_df[col] = le.transform([input_data[col]])

# Predict
prediction = model.predict(input_df)[0]
risk_label = le_dict['Diabetes_Risk'].inverse_transform([prediction])[0]

# Risk messages
risk_messages = {
    "High": {
        "risk": "Your current lifestyle indicates a high risk of developing diabetes. Contributing factors may include high sugar or carb intake, lack of exercise, irregular sleep, and substance use, which increase insulin resistance and blood sugar levels.",
        "tip": "Consult a healthcare provider immediately. Switch to a low-glycemic, fiber-rich diet. Engage in regular physical activity (30–45 minutes daily), reduce or eliminate tobacco and alcohol use, and aim for 7–8 hours of quality sleep per night. Monitor blood glucose levels regularly."
    },
    "Medium": {
        "risk": "You have a moderate risk of developing diabetes. While not critical, your habits—such as inconsistent exercise, moderate sugar intake, or inadequate sleep—could lead to increased risk over time.",
        "tip": "Take preventive steps now. Incorporate more vegetables, whole grains, and lean proteins into your diet. Exercise at least 3–4 times per week, reduce sugar-sweetened beverages, and build a consistent sleep routine. Periodic blood sugar checks are recommended."
    }
}

# Output message if risk is Medium or High
# Output message if risk is Medium or High
if risk_label != "Low":
    print(json.dumps({
        "risk": risk_messages[risk_label]["risk"],
        "tips": risk_messages[risk_label]["tip"]
    }))

# Uncomment below if you want to return a neutral message for Low risk
# else:
#     print(json.dumps({
#         "Risk_Message": "Your current lifestyle suggests a low risk of developing diabetes.",
#         "Health_Tip": "Continue maintaining healthy habits with regular checkups to keep your risk low."
#     }))
