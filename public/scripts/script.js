// Player Registration Back Button
function backPlayerForm(){
    let prevForm = document.getElementById('player-details-form');
    let currentForm = document.getElementById('player-contact-form');

    prevForm.classList.remove('hidden');
    currentForm.classList.add('hidden');
}

// Player Registration Next Button
function nextPlayerForm(){
    let nextForm = document.getElementById('player-contact-form');
    let currentForm = document.getElementById('player-details-form');
    let isJunior;

    nextForm.classList.remove('hidden');
    currentForm.classList.add('hidden');
}

// Event listener for the checkbox to toggle the guardian 2 address field
// const birthdayInput = document.getElementById('dobRegister');
// birthdayInput.addEventListener("change", calculateAge);

// function calculateAge(){
//     const dob = birthdayInput.value;

//     alert(dob);

//     if (!dob) {
//         return;
//     }
//     const today = new Date();
//     const birthday = new Date(dob);

//     let age = today.getFullYear() -  birthday.getFullYear();

//     const monthDifference = today.getMonth() -  birthday.getMonth();
//     // Adjust age if the birthday hasn't occurred yet this year
//     if (monthDifference < 0 || (monthDifference === 0 && today.getDate() <  birthday.getDate())) {
//         age--;
//     }

//     // Open the exisiting parental login radio button
//      if(age > 4 && age <= 12){
//         openParentExisting(age);
//     }
//     // https://www.geeksforgeeks.org/javascript/age-calculator-design-using-html-css-and-javascript/
// }

// function openParentExisting(age){
//     const radioInputs = document.getElementById('if-existing-login');
//     const yes = document.getElementById('existing-login');
//     const email = document.getElementById('if-existing-email');

//     // Check if YES radio is selected
//     if (yes.checked) {
//         console.log(yes.value);
//     }
// }


// Toggle existing parental login details
const existingParentRadios = document.getElementsByName('existing-login');
existingParentRadios.forEach(radio => {
    radio.addEventListener('change', toggleExistingParentDetails);
});

function toggleExistingParentDetails() {
    const input = document.getElementById('existing-login');
    const parentEmail = document.getElementById('existing-parent-email');

    if (input.checked) {        
        parentEmail.classList.add('hidden');
    }
    else{
        parentEmail.classList.remove('hidden');
    }
};

// Event listener for the checkbox to toggle the guardian 2 address field
document.addEventListener("DOMContentLoaded", (event) => {
    const checkbox = document.getElementById('sameAddress');
    const address = document.getElementById('guardian2-address');

    if (!checkbox || !address) {
        console.log('Element not found');
        return;
    }

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            address.classList.add('hidden');
        } else {
            address.classList.remove('hidden');
        }
    });
});



//  FLOWBITE TABLES
// Search Table
if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#search-table", {
        searchable: true,
        sortable: false
    });
}
