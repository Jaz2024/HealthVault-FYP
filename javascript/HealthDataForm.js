const nextButton = document.querySelector('.btn-next');
const prevButton = document.querySelector('.btn-prev');
const steps = document.querySelectorAll('.step');
const form_steps = document.querySelectorAll('.form-step');
let active = 1;

// Function to check if the current step has valid input
const isValidStep = () => {
  const currentFormStep = form_steps[active - 1];
  const inputs = currentFormStep.querySelectorAll('input, select');
  let valid = true;

  // Check if each input is valid (required fields)
  inputs.forEach(input => {
    if (input.hasAttribute('required') && !input.value.trim()) {
      valid = false; // Invalid if the required field is empty
    }
  });

  return valid;
};

// Next button click handler
nextButton.addEventListener('click', () => {
  // Validate the current step before allowing the next step
  if (isValidStep()) {
    active++;
    if (active > steps.length) {
      active = steps.length;
    }
    updateProgress();
  }
});

// Previous button click handler
prevButton.addEventListener('click', () => {
  active--;
  if (active < 1) {
    active = 1;
  }
  updateProgress();
});

// Skip button click handler
const skipButton = document.getElementById('btn-skip');
skipButton.addEventListener('click', () => {
  if (active === 4 || active === 7 || active === 8) {
    active++;
    if (active > steps.length) {
      active = steps.length;
    }
    updateProgress();
  }
});

// Update progress and button visibility
const updateProgress = () => {
  console.log('steps.length => ' + steps.length);
  console.log('active => ' + active);

  // Toggle .active class for each step (progress bar and form steps)
  steps.forEach((step, i) => {
    if (i === (active - 1)) {
      step.classList.add('active'); // Highlight the current step
      form_steps[i].classList.add('active'); // Highlight the current form step
    } else {
      step.classList.remove('active'); // Remove highlight from other steps
      form_steps[i].classList.remove('active'); // Remove highlight from other form steps
    }
  });

  // Update Skip button visibility dynamically
  if (active === 4 || active === 7 || active === 8) {
    skipButton.style.display = 'inline-block'; // Show Skip button on specific steps
  } else {
    skipButton.style.display = 'none'; // Hide Skip button on other steps
  }

  // Update visibility and state of navigation buttons
  const prevButton = document.getElementById('btn-prev');
  const nextButton = document.getElementById('btn-next');
  const submitButton = document.getElementById('btn-submit');

  prevButton.disabled = active === 1; // Disable the previous button on the first step
  nextButton.style.display = active === steps.length ? 'none' : 'inline-block'; // Hide the Next button on the last step
  submitButton.style.display = active === steps.length ? 'inline-block' : 'none'; // Show the Submit button on the last step
};



// Add immunization field
document.getElementById('add-immunization-field-btn').addEventListener('click', function(event) {
  event.preventDefault();

  const immunizationFieldsContainer = document.getElementById('immunization-fields');
  const newField = document.createElement('div');
  newField.classList.add('immunization-fields');
  newField.innerHTML = `
                <div>
                  <input type="text" placeholder="Vaccine Name" name="vaccine" id="vaccine" required>
                  <span id="vaccine-error" class="error-message"></span>
                </div>
                <div>
                 <input type="tel" placeholder="eg. 2020" name="date-administered" id="date-administered" required>
                  <span id="date-administered-error" class="error-message"></span>
                </div>
  `;
  immunizationFieldsContainer.appendChild(newField);

  // Show the delete button
  const deleteButton = document.getElementById('delete-immunization-field-btn');
  deleteButton.style.display = 'inline-block';
});

// Delete last immunization field
document.getElementById('delete-immunization-field-btn').addEventListener('click', function(event) {
  event.preventDefault();

  const immunizationFieldsContainer = document.getElementById('immunization-fields');
  const lastField = immunizationFieldsContainer.lastElementChild;

  if (lastField && immunizationFieldsContainer.children.length > 1) {
    immunizationFieldsContainer.removeChild(lastField);
  }

  // Hide the delete button if only one field remains
  if (immunizationFieldsContainer.children.length === 1) {
    this.style.display = 'none';
  }
});






// Medication section
document.getElementById('add-field-btn').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent page reload

  const medicationFieldsContainer = document.getElementById('medications-fields');
  const newField = document.createElement('div');
  newField.classList.add('medications-fields');
  newField.innerHTML = `
    <div>
      <input type="text" placeholder="" name="medication" id="medication" required>
      <span id="medication-error" class="error-message"></span>
    </div>
    <div>
     <input type="text" placeholder="eg.10mg" name="dosage" id="dosage" required>
     <span id="dosage-error" class="error-message"></span>
    </div>
    <div>
     <input type="text" placeholder="e.g Twice daily" name="freqeuncy" id="freqeuncy" required>
     <span id="frequency-error" class="error-message"></span>
    </div>
  `;
  medicationFieldsContainer.appendChild(newField);

    // Show the delete button
    const deleteButton = document.getElementById('delete-field-btn');
    deleteButton.style.display = 'inline-block';
  });
  
  // Delete last immunization field
  document.getElementById('delete-field-btn').addEventListener('click', function(event) {
    event.preventDefault();
  
    const medicationFieldsContainer = document.getElementById('medications-fields');
    const lastField = medicationFieldsContainer.lastElementChild;
  
    if (lastField && medicationFieldsContainer.children.length > 1) {
      medicationFieldsContainer.removeChild(lastField);
    }
  
    // Hide the delete button if only one field remains
    if (medicationFieldsContainer.children.length === 1) {
      this.style.display = 'none';
    }
  });
  






// Medical Test and Result section
document.getElementById('add-test-btn').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent page reload

  const testFieldsContainer = document.getElementById('test-fields');
  const newField = document.createElement('div');
  newField.classList.add('test-fields');
  newField.innerHTML = `
                  <div>
                  <label>Medical</label>
                  <select name="test" id="test" required>
                    <option value="" disabled selected>Please select</option>
                    <option value="blood-test">Blood Test</option>
                    <option value="urine-test">Urine Test</option>
                    <option value="x-ray">X-ray</option>
                    <option value="mri">MRI</option>
                    <option value="ct-scan">CT Scan</option>
                    <option value="ecg">ECG</option>
                    <option value="ultrasound">Ultrasound</option>
                    <option value="blood-pressure">Blood Pressure Test</option>
                    <option value="glucose-test">Glucose Test</option>
                    <option value="cholesterol-test">Cholesterol Test</option>
                    <option value="liver-function-test">Liver Function Test</option>
                    <option value="kidney-function-test">Kidney Function Test</option>
                    <option value="pregnancy-test">Pregnancy Test</option>
                    <option value="mammogram">Mammogram</option>
                    <option value="pap-smear">Pap Smear</option>
                    <option value="prostate-test">Prostate Test</option>
                    <option value="hiv-test">HIV Test</option>
                    <option value="allergy-test">Allergy Test</option>
                    <option value="skin-biopsy">Skin Biopsy</option>
                    <option value="bone-density-test">Bone Density Test</option>
                  </select>
                </div>
                <div>
                  <label>Result</label>
                  <input type="text" placeholder="" name="result" id="result" required>
                </div>
                <div>
                  <label>Date</label>
                  <div class="grouping">
                    <select name="months" id="months" required>
                      <option value="" disabled selected>Month</option>
                      <option value="">month</option>
                      <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                    <select name="years" id="years" required>
                      <option value="" disabled selected>Year</option>
                      <option value="1980">1980</option>
                      <option value="1981">1981</option>
                      <option value="1982">1982</option>
                      <option value="1983">1983</option>
                      <option value="1984">1984</option>
                      <option value="1985">1985</option>
                      <option value="1986">1986</option>
                      <option value="1987">1987</option>
                      <option value="1988">1988</option>
                      <option value="1989">1989</option>
                      <option value="1990">1990</option>
                      <option value="1991">1991</option>
                      <option value="1992">1992</option>
                      <option value="1993">1993</option>
                      <option value="1994">1994</option>
                      <option value="1995">1995</option>
                      <option value="1996">1996</option>
                      <option value="1997">1997</option>
                      <option value="1998">1998</option>
                      <option value="1999">1999</option>
                      <option value="2000">2000</option>
                      <option value="2001">2001</option>
                      <option value="2002">2002</option>
                      <option value="2003">2003</option>
                      <option value="2004">2004</option>
                      <option value="2005">2005</option>
                      <option value="2006">2006</option>
                      <option value="2007">2007</option>
                      <option value="2008">2008</option>
                      <option value="2009">2009</option>
                      <option value="2010">2010</option>
                      <option value="2011">2011</option>
                      <option value="2012">2012</option>
                      <option value="2013">2013</option>
                      <option value="2014">2014</option>
                      <option value="2015">2015</option>
                      <option value="2016">2016</option>
                      <option value="2017">2017</option>
                      <option value="2018">2018</option>
                      <option value="2019">2019</option>
                      <option value="2020">2020</option>
                      <option value="2021">2021</option>
                      <option value="2022">2022</option>
                      <option value="2023">2023</option>
                      <option value="2024">2024</option>
                      <option value="2025">2024</option>
                    </select>
                  </div>
                </div>
  `;
  testFieldsContainer.appendChild(newField);

    // Show the delete button
    const deleteButton = document.getElementById('delete-test-btn');
    deleteButton.style.display = 'inline-block';
  });
  
  // Delete last immunization field
  document.getElementById('delete-test-btn').addEventListener('click', function(event) {
    event.preventDefault();
  
    const testFieldsContainer = document.getElementById('test-fields');
    const lastField = testFieldsContainer.lastElementChild;
  
    if (lastField && testFieldsContainer.children.length > 1) {
      testFieldsContainer.removeChild(lastField);
    }
  
    // Hide the delete button if only one field remains
    if (testFieldsContainer.children.length === 1) {
      this.style.display = 'none';
    }
  });







document.getElementById('add-provider-btn').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent page reload

  const providerFieldsContainer = document.getElementById('provider-fields');
  const newField = document.createElement('div');
  newField.classList.add('provider-fields'); // Add class for styling
  newField.innerHTML = `
    <div>
      <label>Provider Type</label>
      <input type="text" placeholder="eg.Cardiologist" name="provider-type" id="provider-type" required>    </div>
    <div>
      <label>Name</label>
      <input type="text" placeholder="" name="provider-name" id="provider-name" required>
    </div>
    <div>
      <label>Contact Info</label>
      <input type="text" placeholder="" name="provider-tel" id="provider-tel" required>
    </div>
    <div>
      <label>Facility Name</label>
      <input type="text" placeholder="" name="facility-name" id="facility-name" required>
    </div>
    <div>
      <label>Facility Address</label>
      <input type="text" placeholder="" name="facility-address" id="facility-address" required>
    </div>
  `;

  providerFieldsContainer.appendChild(newField);

  // Show the delete button
  const deleteButton = document.getElementById('delete-provider-btn');
  deleteButton.style.display = 'inline-block';
});




document.getElementById('delete-provider-btn').addEventListener('click', function(event) {
  event.preventDefault(); // Prevent page reload

  const providerFieldsContainer = document.getElementById('provider-fields');
  const lastField = providerFieldsContainer.lastElementChild;

  if (lastField && providerFieldsContainer.children.length > 1) {
    providerFieldsContainer.removeChild(lastField);
  }

  // Hide the delete button if only one field remains
  if (providerFieldsContainer.children.length === 1) {
    this.style.display = 'none';
  }
});







// Function to calculate BMI
function calculateBMI() {
  var weight = document.getElementById('weight').value;
  var height = document.getElementById('height').value;

  if (weight && height) {
      // Convert height from cm to meters
      height = height / 100;
      
      // Calculate BMI
      var bmi = weight / (height * height);
      
      // Display the BMI result in the text input
      document.getElementById('bmiResult').value = bmi.toFixed(2); // Rounded to 2 decimal places
  } else {
      document.getElementById('bmiResult').value = '0';
  }
}


function toggleYearField() {
  var tobaccoUse = document.getElementById("tobaccoUse").value;
  var yearQuitDiv = document.getElementById("yearQuitDiv");
  var yearQuitInput = document.getElementById("yearQuit");

  if (tobaccoUse === "quit") {
    yearQuitDiv.style.display = "block"; // Show the year field
    yearQuitInput.removeAttribute("disabled"); // Enable the input
    yearQuitInput.setAttribute("required", "required"); // Make it required
  } else {
    yearQuitDiv.style.display = "none"; // Hide the year field
    yearQuitInput.setAttribute("disabled", "disabled"); // Disable the input
    yearQuitInput.removeAttribute("required"); // Remove required
  }
}





document.addEventListener('DOMContentLoaded', function () {
  const nextButton = document.getElementById('btn-next');
  const prevButton = document.getElementById('btn-prev');
  const skipButton = document.getElementById('btn-skip');
  const submitButton = document.getElementById('btn-submit');

  const formSteps = document.querySelectorAll('.form-step');
  let currentStep = 0;

  // Function to show the current step
  function showStep(step) {
    formSteps.forEach((formStep, index) => {
      formStep.style.display = index === step ? 'block' : 'none';
    });

    prevButton.disabled = step === 0; // Disable back button on first step
    nextButton.style.display = step === formSteps.length - 1 ? 'none' : 'inline-block'; // Hide Next on the last step
    submitButton.style.display = step === formSteps.length - 1 ? 'inline-block' : 'none'; // Show Submit on the last step

    // Show Skip button on specific steps
    skipButton.style.display = step === 3 || step === 6 || step === 7 ? 'inline-block' : 'none';
  }

  // Function to validate the current step
  function validateStep() {
    const inputs = formSteps[currentStep].querySelectorAll('input[required], select[required]');
    let valid = true;

    inputs.forEach(input => {
      if (!input.value) {
        valid = false;
        input.classList.add('error'); // Add error class for styling

        // Remove the shake animation after it finishes to allow re-triggering
        setTimeout(() => input.classList.remove('error'), 500);
      } else {
        input.classList.remove('error'); // Remove error class if valid
      }
    });

    return valid;
  }

  // Next button click event
  nextButton.addEventListener('click', function () {
    if (validateStep()) {
      currentStep++;
      if (currentStep >= formSteps.length) {
        currentStep = formSteps.length - 1; // Prevent going out of bounds
      }
      showStep(currentStep);
    }
  });

  // Skip button click event
  skipButton.addEventListener('click', function () {
    currentStep++;
    if (currentStep >= formSteps.length) {
      currentStep = formSteps.length - 1; // Prevent going out of bounds
    }
    showStep(currentStep);
  });

  // Previous button click event
  prevButton.addEventListener('click', function () {
    currentStep--;
    if (currentStep < 0) {
      currentStep = 0; // Prevent going out of bounds
    }
    showStep(currentStep);
  });

// Submit button click event
submitButton.addEventListener('click', function (event) {
  event.preventDefault(); // Prevent the default form submission
  if (validateStep()) {
    // Final submission logic here (no message, just redirect)
    window.location.href = 'dashboard.php'; // Redirect to the dashboard page
  }
});


  // Initialize the form
  showStep(currentStep);
});
