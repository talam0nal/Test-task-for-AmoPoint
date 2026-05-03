<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
    <meta charset="UTF-8">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<h2>Выберите тип</h2>

<select id="type-select">
    <option value="">-- выберите --</option>
    @foreach($types as $type)
        <option value="{{ $type }}">{{ $type }}</option>
    @endforeach
</select>

<hr>

<div id="form-fields">
    <input type="text" name="type1_name" placeholder="Type1 Name">
    <input type="text" name="type1_email" placeholder="Type1 Email">

    <input type="text" name="type2_phone" placeholder="Type2 Phone">
    <input type="text" name="type2_address" placeholder="Type2 Address">

    <input type="text" name="type3_company" placeholder="Type3 Company">
</div>

<script>
$(document).ready(function () {

    function updateFields(selectedType) {
        // Скрываем все поля
        $('#form-fields input').addClass('hidden');

        if (!selectedType) return;

        // Показываем только те, у которых name содержит выбранный тип
        $('#form-fields input').filter(function () {
            return $(this).attr('name').includes(selectedType);
        }).removeClass('hidden');
    }

    // при изменении select
    $('#type-select').on('change', function () {
        const selectedType = $(this).val();
        updateFields(selectedType);
    });

});

//Для прода можно было бы использовать следующее решение:
/*
document.querySelector('#type-select').addEventListener('change', function () {
    const selected = this.value;

    document.querySelectorAll('#form-fields input').forEach(input => {
        input.style.display = input.name.includes(selected) ? 'block' : 'none';
    });
});
*/
</script>

</body>
</html>
