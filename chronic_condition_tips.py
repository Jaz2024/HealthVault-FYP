import json
import sys

CC_data = {
    "Chronic_Condition": [
        {
            "use": "Addictions – substance and alcohol, etc.",
            "tips": "Nourish your body with healthy food and water to support your recovery from substance or alcohol addiction."
        },
        {
            "use": "ADHD",
            "tips": "Get regular physical activity and it can help improve focus and reduce restlessness related to ADHD."
        },
        {
            "use": "Alcohol addiction",
            "tips": "Stay hydrated and eat nutritious meals which can help your body heal from the effects of alcohol addiction."
        },
        {
            "use": "Allergic rhinitis/rhinitis/sinusitis/rhinosinusitis",
            "tips": "Avoid allergens such as pollen, dust, or mold that can trigger rhinitis or rhinosinusitis symptoms."
        },
        {
            "use": "Allergy/anaphylaxis",
            "tips": "Learn to identify and strictly avoid your known allergy triggers, whether they are foods, medications, or insect stings."
        },
        {
            "use": "Amputations",
            "tips": "Keep the skin around your amputation site clean and dry to prevent infections and irritation."
        },
        {
            "use": "Amnesia",
            "tips": "Ensure proper sleep and nutrition, as both are essential for brain health and memory recovery."
        },
        {
            "use": "Anaemia",
            "tips": "Pair iron-rich foods with vitamin C sources (like oranges or tomatoes) to boost iron absorption."
        },
        {
            "use": "Angina",
            "tips": "Avoid physical or emotional stress, as it can trigger angina symptoms such as practice relaxation techniques like deep breathing."
        },
        {
            "use": "Angiooedema",
            "tips": "Manage stress, as emotional triggers can sometimes contribute to angioedema flare-ups."
        },
        {
            "use": "Anklosing spondylitis (and other arthritic conditions)",
            "tips": "Practice good posture daily to help reduce stiffness and maintain spinal flexibility in ankylosing spondylitis."
        },
        {
            "use": "Antenatal screening for haemoglobinoapthies – sickle cell and thalassemia, Downs",
            "tips": "Early screening helps identify if you or your baby may carry haemoglobin disorders or chromosomal differences."
        },
        {
            "use": "Anxiety and stress disorders (including complex and post-traumatic stress disorders)",
            "tips": "If you're living with anxiety or stress disorders, practice grounding techniques like deep breathing or the 5-4-3-2-1 method during overwhelming moments."
        },
        {
            "use": "Aphasia",
            "tips": "If you're living with aphasia, work closely with a speech-language pathologist to improve communication skills and recovery."
        },
        {
            "use": "Ataxia’s",
            "tips": "Monitor and manage any other underlying conditions that may contribute to ataxia symptoms."
        },
        {
            "use": "Autism",
            "tips": "Incorporate relaxation techniques, such as deep breathing exercises or calming music, to help reduce stress associated with autism."
        },
        {
            "use": "Autoimmune disorders (e.g. lupus, Sjögrens syndrome)",
            "tips": "Protect your skin from the sun by wearing sunscreen and protective clothing to prevent lupus flare-ups."
        },
        {
            "use": "Blood disorders",
            "tips": "Exercise regularly, but consult your doctor for safe activity guidelines, especially if you have a blood disorder that affects mobility or stamina."
        },
        {
            "use": "Brain injuries (including stroke and TIAs)",
            "tips": "Prioritize rest and sleep to support the brain’s recovery and reduce fatigue, a common issue after a stroke or TIA."
        },
        {
            "use": "Bronchopulmonary dysplasia (chronic lung disease of infancy)",
            "tips": "Ensure regular follow-up appointments to assess lung health and development, as early detection of complications is key."
        },
        {
            "use": "Burn injuries",
            "tips": "If you've suffered a burn injury, seek immediate medical attention, especially if the burn is severe, to prevent complications like infection."
        },
        {
            "use": "Cancer",
            "tips": "If you’ve been diagnosed with cancer, work closely with your oncologist to create a personalized treatment plan tailored to your specific type and stage of cancer."
        },
        {
            "use": "Cardiac arrhythmias",
            "tips": "Keep your stress levels low through relaxation techniques like deep breathing, meditation, or yoga to avoid exacerbating arrhythmias."
        },
        {
            "use": "Cerebral palsy",
            "tips": "Ensure proper nutrition, as Cerebral palsy can affect muscle growth and digestion; consult with a dietitian to create an appropriate diet plan."
        },
        {
            "use": "Chronic fatigue syndrome/ME",
            "tips": "If you have Chronic fatigue syndrome/ME, follow a balanced diet to support your body’s energy needs and combat any nutritional deficiencies that may arise."
        },
        {
            "use": "Chronic kidney disease",
            "tips": "Managing blood pressure is crucial if you’re dealing with Chronic Kidney Disease (CKD), as high pressure can lead to further kidney damage."
        },
        {
            "use": "Chronic obstructive pulmonary disease",
            "tips": "If you have Chronic Obstructive Pulmonary Disease (COPD), follow a healthy diet rich in fruits, vegetables, and lean proteins to support lung function and reduce inflammation."
        },
        {
            "use": "Chronic pain",
            "tips": "Pacing daily activities and avoiding overexertion can help manage energy levels and prevent flare-ups for those with chronic pain."
        },
        {
            "use": "Chronic sinusitis",
            "tips": "Avoiding allergens and irritants, such as smoke and strong odors, can help reduce flare-ups of chronic sinusitis."
        },
        {
            "use": "Coeliac disease",
            "tips": "Reading food labels carefully is important when living with coeliac disease to ensure all foods are gluten-free."
        },
        {
            "use": "Connective tissue diseases",
            "tips": "Regular exercise, particularly low-impact activities like swimming or walking, can help maintain mobility and reduce stiffness in connective tissue diseases."
        },
        {
            "use": "Congestive heart failure",
            "tips": "Taking medications as prescribed is crucial for managing congestive heart failure (CHF) and controlling symptoms such as fluid buildup and high blood pressure."
        },
        {
            "use": "Coronary heart disease",
            "tips": "Regular physical activity, such as brisk walking or cycling, can improve heart health and circulation for those with coronary heart disease (CHD)."
        },
        {
            "use": "Crohn’s disease",
            "tips": "Managing stress through relaxation techniques like meditation or yoga can help reduce flare-ups and ease symptoms of Crohn’s disease."
        },
        {
            "use": "Cystic Fibrosis",
            "tips": "A high-calorie, high-fat diet is essential for individuals with cystic fibrosis to ensure proper nutrition and support lung function."
        },
        {
            "use": "Dementia",
            "tips": "Staying mentally active through activities like puzzles, reading, or memory exercises can help maintain cognitive function in dementia."
        },
        {
            "use": "Depression",
            "tips": "If you have depression, maintaining a regular routine, including getting up at the same time each day, can help reduce feelings of hopelessness."
        },
        {
            "use": "Digestive conditions, stomach ulcers, oesophagus, reflux",
            "tips": "Eating smaller, more frequent meals rather than large meals can prevent excessive acid production and ease symptoms of stomach ulcers and reflux."
        },
        {
            "use": "Dizziness",
            "tips": "If you have dizziness, ensure you're getting enough rest, as fatigue can contribute to feelings of lightheadedness and imbalance."
        },
        {
            "use": "Diabetes: Type I",
            "tips": "If you have Type 1 diabetes, it's important to monitor your blood sugar levels regularly to ensure they stay within a healthy range."
        },
        {
            "use": "Diabetes: Type II",
            "tips": "If you have Type 2 diabetes, monitoring your blood sugar regularly can help you maintain control and prevent complications."
        },
        {
            "use": "Dyslexia or dyspraxia",
            "tips": "If you have dyslexia or dyspraxia, practicing regular physical activities like swimming or yoga can help improve coordination and motor skills."
        },
        {
            "use": "Eating disorders (anorexia/bulimia)",
            "tips": "Regular physical activity, under the guidance of a healthcare provider, can help improve mental well-being and promote healthy body image in eating disorder recovery."
        },
        {
            "use": "Eczema",
            "tips": "Avoiding known triggers, such as harsh soaps, hot water, or certain fabrics, can help manage eczema symptoms and prevent irritation."
        },
        {
            "use": "Endocrine disorders (thyrotoxicosis, hypothyroidism, hypogonadism, Cushing syndrome, Addison’s disease)",
            "tips": "If you have an endocrine disorder, taking prescribed medications like thyroid hormone replacement or corticosteroids as directed by your healthcare provider is crucial to managing your condition."
        },
        {
            "use": "Epilepsy",
            "tips": "Avoiding seizure triggers such as lack of sleep, stress, flashing lights, or certain substances can help reduce the frequency of seizures in epilepsy."
        },
        {
            "use": "Fibromyalgia/chronic widespread pain",
            "tips": "If you have fibromyalgia, getting adequate rest and practicing good sleep hygiene can help reduce fatigue and improve pain management."
        },
        {
            "use": "Gout",
            "tips": "Drinking plenty of water throughout the day can help flush uric acid from your body and prevent the buildup that causes gout."
        },
        {
            "use": "Gynaecological problems, chronic pelvic pain",
            "tips": "If you experience chronic pelvic pain, applying heat to the lower abdomen can ease cramping and reduce pain."
        },
        {
            "use": "Haemophilia and other coagulation disorders",
            "tips": "Wearing protective gear during physical activities is crucial for individuals with coagulation disorders to prevent injury and internal bleeding."
        },
        {
            "use": "Heart failure",
            "tips": "Following a low-sodium diet helps reduce fluid retention and ease the workload on your heart when living with heart failure."
        },
        {
            "use": "Hepatitis B",
            "tips": "If you're living with Hepatitis B, always take antiviral medications as prescribed to help reduce liver damage."
        },
        {
            "use": "Hepatitis C",
            "tips": "Regular follow-up with your healthcare provider is key to monitor liver function and track treatment progress in Hepatitis C"
        },
        {
            "use": "Hypertension",
            "tips": "Monitoring your blood pressure at home helps track your condition and spot early signs of change in hypertension."
        },
        {
            "use": "Learning disabilities",
            "tips": "If you have a learning disability, using personalized learning tools or apps can help improve understanding and retention."
        },
        {
            "use": "Lung fibrosis",
            "tips": "Using prescribed oxygen therapy as directed is important in managing lung fibrosis and improving daily function."
        },
        {
            "use": "Lupus",
            "tips": "Getting enough rest and pacing your activities can help manage fatigue commonly experienced with lupus."
        },
        {
            "use": "Malaria",
            "tips": "Using mosquito nets and repellents is key to preventing new malaria infections, especially in high-risk areas."
        },
        {
            "use": "Medically unexplained symptoms",
            "tips": "Gentle, low-impact exercise, such as walking or swimming, can help reduce muscle tension and improve well-being when managing medically unexplained symptoms."
        },
        {
            "use": "Migraine",
            "tips": "Staying hydrated by drinking plenty of water throughout the day can help reduce the risk of migraines."
        },
        {
            "use": "Mood disorders (not only depression, but mania and bipolar disorders)",
            "tips": "Keeping track of your moods and triggers in a journal can help you identify patterns and communicate more effectively with your healthcare provider about your mood disorder."
        },
        {
            "use": "Motor neurone disease",
            "tips": "Using assistive devices like walking aids, wheelchairs, or modified tools can help you maintain independence while managing motor neurone disease."
        },
        {
            "use": "Multimorbidity",
            "tips": "Staying organized by using medication reminders or pillboxes can help you manage multiple prescriptions when living with multimorbidity."
        },
        {
            "use": "Multisystem autoimmune diseases (MSAIDs, including lupus)",
            "tips": "Protecting your skin from UV exposure is crucial if you have lupus, as sunlight can trigger flare-ups and worsen symptoms of MSAIDs"
        },
        {
            "use": "Muscular dystrophy(ies)",
            "tips": "Maintaining a well-balanced diet with proper nutrition helps support overall health and manage weight, which is important for people with muscular dystrophy."
        },
        {
            "use": "Neuralgias (including, head and back pain)",
            "tips": "Managing stress through relaxation techniques like deep breathing or meditation can help alleviate tension that may trigger or worsen neuralgia symptoms."
        },
        {
            "use": "Newborn screening programme diseases, including thyroid disease, hearing loss",
            "tips": "Regular follow-up appointments with pediatric endocrinologists and audiologists ensure your newborn receives the necessary treatments and support for thyroid disease and hearing loss."
        },
        {
            "use": "Obesity",
            "tips": "Tracking your meals and exercise can help you stay accountable and make healthier choices when managing obesity."
        },
        {
            "use": "Obstructive sleep apnoea",
            "tips": "Maintaining a healthy weight through diet and exercise can help reduce the severity of obstructive sleep apnoea by alleviating pressure on your airway."
        },
        {
            "use": "Occupational lung disease (various)",
            "tips": "If you have occupational lung disease, regular check-ups with a respiratory specialist can help monitor lung function and detect any changes early."
        },
        {
            "use": "Osteoarthritis",
            "tips": "Stretching and strengthening exercises tailored to your condition can improve mobility and support the muscles around the joints affected by osteoarthritis."
        },
        {
            "use": "Osteoporosis",
            "tips": "Weight-bearing exercises, such as walking, jogging, or strength training, can help strengthen bones and reduce the risk of fractures if you have osteoporosis."
        },
        {
            "use": "Other slowly degenerative neurological conditions",
            "tips": "If you have degenerative neurological conditions, regular physical activity, including walking or stretching exercises, can help maintain motor skills and reduce stiffness."
        },
        {
            "use": "Peripheral vascular disease",
            "tips": "If you have peripheral vascular disease, regular physical activity, such as walking, can improve blood flow and reduce symptoms of leg pain and cramping."
        },
        {
            "use": "Personality disorders",
            "tips": "If you have personality disorders, establishing a daily routine can bring structure and reduce feelings of chaos or unpredictability."
        },
        {
            "use": "Phobias",
            "tips": "If you have phobias, gradual exposure to the feared object or situation in a controlled environment can help reduce anxiety over time."
        },
        {
            "use": "Physical disabilities",
            "tips": "If you have a physical disability, staying active through adapted physical activities can improve strength, flexibility, and mood."
        },
        {
            "use": "Polycystic ovary disease",
            "tips": "If you have polycystic ovary disease, maintaining a healthy weight through diet and exercise can help regulate hormones and menstrual cycles."
        },
        {
            "use": "Post-traumatic stress",
            "tips": "Therapy like EMDR or trauma-focused CBT can be a powerful step in healing emotional wounds from past trauma."
        },
        {
            "use": "Progressive supranuclear palsy",
            "tips": "Speech therapy is highly recommended when Progressive Supranuclear Palsy starts to impact communication or swallowing."
        },
        {
            "use": "Psoriasis",
            "tips": "Managing psoriasis often starts with moisturizing daily to soothe dryness and reduce flare-ups."
        },
        {
            "use": "Rare disease, genetic disorders",
            "tips": "Personalized treatment plans are essential when dealing with a rare disorder, as symptoms can vary widely."
        },
        {
            "use": "Sarcoidosis",
            "tips": "Managing sarcoidosis involves a balanced approach, including medications to control inflammation and prevent flare-ups."
        },
        {
            "use": "Sensory problems/disabilities (deafness/blindness)",
            "tips": "When living with sensory disabilities, learning alternative communication methods such as sign language or Braille can enhance independence."
        },
        {
            "use": "Severe skin conditions",
            "tips": "Wearing loose, breathable clothing can reduce friction and irritation if you suffer from severe skin conditions like eczema or psoriasis."
        },
        {
            "use": "Sickle cell disease",
            "tips": "Eating a healthy, balanced diet rich in vitamins and minerals can support overall health and well-being for those with sickle cell disease."
        },
        {
            "use": "Skin conditions",
            "tips": "Keeping your skin hydrated is essential for managing skin conditions, especially during dry or cold weather."
        },
        {
            "use": "Sleep disorders",
            "tips": "If you experience sleep disorders, establishing a consistent bedtime routine can help signal to your body when it’s time to wind down."
        },
        {
            "use": "Speech deficits",
            "tips": "Consistent practice and patience are key when managing speech deficits, as speech therapy can take time to show noticeable improvement."
        },
        {
            "use": "Spina bifida",
            "tips": "If you have spina bifida, practicing good skin care and avoiding pressure sores is vital, especially if you have limited sensation in certain areas."
        },
        {
            "use": "Spinal injuries",
            "tips": "Protecting your spine with proper posture is crucial for managing spinal injuries, as it can reduce stress on the injured area."
        },
        {
            "use": "Stroke/transient ischaemic attacks",
            "tips": "Regular monitoring of your heart health is important if you’ve had a stroke or TIA, as conditions like atrial fibrillation can contribute to stroke risk."
        },
        {
            "use": "Tuberculosis",
            "tips": "If you have tuberculosis (TB), it's crucial to complete the full course of antibiotics to fully eradicate the infection and prevent resistance."
        },
        {
            "use": "Urinary Incontinence",
            "tips": "If you have urinary incontinence, practicing pelvic floor exercises, such as Kegel exercises, can help strengthen muscles and improve bladder control."
        },
        {
            "use": "Urticaria",
            "tips": "If you have urticaria, identifying and avoiding triggers such as certain foods, medications, or environmental factors can help prevent flare-ups."
        }
        
    ]
}

def get_Chronic_Condition(use):
    for entry in CC_data["Chronic_Condition"]:
        if entry["use"] == use:
            return entry["tips"]
    return "Unknown use type", "No advice available."

input_data = sys.stdin.read()
data = json.loads(input_data)
Chronic_Condition_input = data.get("Chronic_Condition", "Unknown")

tips = get_Chronic_Condition(Chronic_Condition_input)

output = {
    "tips": tips
}

print(json.dumps(output))
