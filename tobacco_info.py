import json
import sys

tobacco_data = {
    "tobacco_use": [
        {
            "use": "Daily",
            "risk": "Daily tobacco use significantly increases the risk of severe health issues such as lung cancer, heart disease, and chronic respiratory conditions.",
            "tips": "Quit smoking to drastically reduce the risk of lung cancer, heart disease, and other chronic conditions. Seek professional help and consider nicotine replacement therapies."
        },
        {
            "use": "Occasionally",
            "risk": "Occasional tobacco use may still elevate the risk of certain health problems, including increased likelihood of respiratory issues and cardiovascular strain.",
            "tips": "Reduce or eliminate tobacco use to lower the risk of cardiovascular and respiratory issues. Consider behavioral changes and seek medical guidance for cessation."
        },
        {
            "use": "Never Smoked",
            "risk": "",
            "tips": ""
        }
    ]
}

def get_tobacco_info(use):
    for entry in tobacco_data["tobacco_use"]:
        if entry["use"] == use:
            return entry["risk"], entry["tips"]
    return "Unknown use type", "No advice available."

input_data = sys.stdin.read()
data = json.loads(input_data)
tobacco_use_input = data.get("Tobacco_Use", "Unknown")

risk, tips = get_tobacco_info(tobacco_use_input)

output = {
    "risk": risk,
    "tips": tips
}

print(json.dumps(output))
