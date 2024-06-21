//funkcja obsługująca przycisk usuń wiersz. Pokazuje alert.
document.addEventListener('DOMContentLoaded', () => {
    const deleteBodyButtons = document.querySelectorAll('.delete-body-button');
    const deleteMenuButtons = document.querySelectorAll('.delete-menu-button');

    deleteBodyButtons.forEach(button => {
        button.addEventListener('click', e => {
            const id = button.dataset.id;
            const confirmed = confirm('Czy na pewno chcesz usunąć ten element?');

            if (confirmed) {
                window.location.href = `/admin/body/delete/${id}`;
            }
        });
    });

    deleteMenuButtons.forEach(button => {
        button.addEventListener('click', e => {
            const id = button.dataset.id;
            const confirmed = confirm('Czy na pewno chcesz usunąć ten element?');

            if (confirmed) {
                window.location.href = `/admin/menu/delete/${id}`;
            }
        });
    });
});

/**sorting */
document.querySelectorAll('.up, .down').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        let id = this.getAttribute('data-id');
        let direction = this.classList.contains('up') ? 'up' : 'down';
        fetch(`/admin/body/${direction}/${id}`, {
            method: 'POST'
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to move item');
            }
        });
    });
});
