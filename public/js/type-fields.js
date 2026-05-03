(function () {

    function updateFields(selectedType) {
        const inputs = document.querySelectorAll('#form-fields input');

        inputs.forEach(input => {
            input.style.display = 'none';
        });

        if (!selectedType) return;

        inputs.forEach(input => {
            if (input.name.includes(selectedType)) {
                input.style.display = 'block';
            }
        });
    }

    function init() {
        const select = document.getElementById('type-select');
        if (!select) return;

        select.addEventListener('change', function () {
            updateFields(this.value);
        });
    }

    // запуск после загрузки DOM
    document.addEventListener('DOMContentLoaded', init);

})();
