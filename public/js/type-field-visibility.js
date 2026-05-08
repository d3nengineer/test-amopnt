(function () {
    function initTypeFieldVisibility() {
        var typeSelect = document.querySelector('select[name="type_val"]');

        if (!typeSelect) {
            return;
        }

        function getVisibleContainer(element) {
            return element.closest('p') || element;
        }

        function updateVisibleFields() {
            var selectedType = typeSelect.value;
            var fields = document.querySelectorAll('[name]');

            fields.forEach(function (field) {
                if (field === typeSelect) {
                    return;
                }

                var fieldName = field.getAttribute('name') || '';
                var container = getVisibleContainer(field);

                container.style.display = fieldName.includes(selectedType) ? '' : 'none';
            });
        }

        typeSelect.addEventListener('change', updateVisibleFields);
        updateVisibleFields();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTypeFieldVisibility);
    } else {
        initTypeFieldVisibility();
    }
}());
