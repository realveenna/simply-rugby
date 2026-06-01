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

// Event listener for the checkbox to toggle the guardian 2 address 
document.addEventListener("DOMContentLoaded", (event) => {
    const checkbox = document.getElementById('sameAddress');
    const address = document.getElementById('guardian2-address');


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
