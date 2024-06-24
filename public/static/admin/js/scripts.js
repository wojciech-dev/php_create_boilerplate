//funkcja obsługująca przycisk usuń wiersz. Pokazuje alert.
document.addEventListener('DOMContentLoaded', () => {
const deleteBodyButtons = document.querySelectorAll('.delete-body-button');
const deleteMenuButtons = document.querySelectorAll('.delete-menu-button');
const deleteBannerButtons = document.querySelectorAll('.delete-banner-button');

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

deleteBannerButtons.forEach(button => {
    button.addEventListener('click', e => {
        const id = button.dataset.id;
        const confirmed = confirm('Czy na pewno chcesz usunąć ten element?');

        if (confirmed) {
            window.location.href = `/admin/banner/delete/${id}`;
        }
    });
});


/*sortowanie wierszy + podswietlanie w body */
document.querySelectorAll('.table_body .up, .table_body .down').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        let row = this.closest('tr');
        let id = this.getAttribute('data-id');
        let direction = this.classList.contains('up') ? 'up' : 'down';
        fetch(`/admin/body/${direction}/${id}`, {
            method: 'POST'
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                if (direction === 'up') {
                    let prevRow = row.previousElementSibling;
                    if (prevRow) {
                        row.parentNode.insertBefore(row, prevRow);
                    }
                } else {
                    let nextRow = row.nextElementSibling;
                    if (nextRow) {
                        row.parentNode.insertBefore(nextRow, row);
                    }
                }
                row.classList.add('highlight');
                setTimeout(function() {
                    row.classList.remove('highlight');
                }, 1000);
            } else {
                alert('Failed to move item');
            }
        });
    });
});

/*sortowanie wierszy + podswietlanie w banner */
document.querySelectorAll('.table_banner .up, .table_banner .down').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        let row = this.closest('tr');
        let id = this.getAttribute('data-id');
        let direction = this.classList.contains('up') ? 'up' : 'down';
        fetch(`/admin/banner/${direction}/${id}`, {
            method: 'POST'
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                if (direction === 'up') {
                    let prevRow = row.previousElementSibling;
                    if (prevRow) {
                        row.parentNode.insertBefore(row, prevRow);
                    }
                } else {
                    let nextRow = row.nextElementSibling;
                    if (nextRow) {
                        row.parentNode.insertBefore(nextRow, row);
                    }
                }
                row.classList.add('highlight');
                setTimeout(function() {
                    row.classList.remove('highlight');
                }, 1000);
            } else {
                alert('Failed to move item');
            }
        });
    });
});


});