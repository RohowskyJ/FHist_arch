/* select multiple check boxes */
// Sobald DOM geladen ist
document.addEventListener('DOMContentLoaded', function () {
    // Globale Master-Checkbox
    const checkAll = document.getElementById('check_all');
console.log('checkAll ',checkAll);
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            const all = document.querySelectorAll("input[name='db_gruppe[]']");
            all.forEach(cb => cb.checked = checkAll.checked);
			console.log('all cb ',all);
        });
    }

    // Gruppen-Master-Buttons (Gruppe / Leeren)
    const groupButtons = document.querySelectorAll('.btn-chip[data-master]');

    groupButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const groupKey = this.getAttribute('data-master'); // z.B. "ar"
            const mode = this.getAttribute('data-mode');       // "all" oder "none"
            const check = mode === 'all';
console.log('GroupBtn ',groupButtons);
            // Alle Checkboxen mit passender CSS-Klasse toggeln
            const groupCheckboxes = document.querySelectorAll('.group-' + groupKey);
            groupCheckboxes.forEach(cb => cb.checked = check);
			console.log('checkBoxes ',groupCheckboxes);
        });
    });
});