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

// Calculate Age
// Define a JavaScript function called calculate_age with parameter dob (date of birth)
// function calculateAge(dob) { 
//     const today = new Date();
//     const birthDate = new Date(dob);
//     let age = today.getFullYear() - birthDate.getFullYear();

//     const monthDiff = today.getMonth() - birthDate.getMonth();

//     if (monthDiff < 0 || 
//        (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
//         age--;
//     }

//     return age;
// }

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
