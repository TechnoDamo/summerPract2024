function showIndexInput() {
    var options = document.getElementById('options');
    var nameInput = document.getElementById('nameInput');
    var bonus1RangeInput = document.getElementById('bonus1RangeInput');
    if (options.value === '3') {
        nameInput.style.display = 'block';
        bonus1RangeInput.style.display = 'none';
    } else if (options.value === '4') {
        nameInput.style.display = 'none';
        bonus1RangeInput.style.display = 'block';
    } else {
        nameInput.style.display = 'none';
        bonus1RangeInput.style.display = 'none';
    }
}