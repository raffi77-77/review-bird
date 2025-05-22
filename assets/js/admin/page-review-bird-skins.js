function activateParent(radioInput) {
    const items = document.querySelectorAll('div.rw-skin-select-radio');
    items.forEach(item => item.classList.remove('active'));
    const target = radioInput.closest('.rw-skin-template-header')?.querySelector('.rw-skin-select-radio');
    if (target) {
        target.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Activate parent on change
    document.querySelectorAll('input.rw-skin-select-radio-in').forEach(input => {
        input.addEventListener('change', function (e) {
            activateParent(this);
        });
    });
    // Find checked radio
    const checkedInput = document.querySelector('input.rw-skin-select-radio-in:checked');
    if (checkedInput) {
        activateParent(checkedInput);
    }
});