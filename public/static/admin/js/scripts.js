document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(button => {
        button.addEventListener('click', e => {
            const id = button.dataset.id;
            const confirmed = confirm('Czy na pewno chcesz usunąć ten element?');

            if (confirmed) {
                window.location.href = `/admin/body/delete/${id}`;
            }
        });
    });
});
