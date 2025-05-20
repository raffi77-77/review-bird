function activateParent(label) {
    const items = document.querySelectorAll('div.rw-skin-select-radio');
    items.forEach(item => item.classList.remove('active'));
    const target = label.closest('.rw-skin-template-header')?.querySelector('.rw-skin-select-radio');
    if (target) {
        target.classList.add('active');
    }
}
document.addEventListener('DOMContentLoaded', function () {
    // Attach click event to all labels
    const labels = document.querySelectorAll('label.rw-skin-template-desc');
    labels.forEach(label => {
        label.addEventListener('click', function () {
            activateParent(this);
        });
    });
    // Find checked radio
    const checkedInput = document.querySelector('input.rw-skin-select-radio-in:checked');
    if (checkedInput) {
        const label = checkedInput.closest('.rw-skin-template-header')?.querySelector('label.rw-skin-template-desc');
        if (label) {
            activateParent(label);
        }
    }
});