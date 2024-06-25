document.addEventListener('DOMContentLoaded', () => {
    
//funkcja obsługująca przycisk usuń wiersz. Pokazuje alert.
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const id = button.dataset.id;
            const type = button.dataset.type;
            const confirmed = confirm('Czy na pewno chcesz usunąć ten element?');
    
            if (confirmed) {
                window.location.href = `/admin/${type}/delete/${id}`;
            }
        });
    });
    
    /**sortowanie rekordów w panleu strzałkami .up .down */
    document.querySelectorAll('.table_body .up, .table_body .down, .table_banner .up, .table_banner .down').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let row = this.closest('tr');
            let id = this.getAttribute('data-id');
            let direction = this.classList.contains('up') ? 'up' : 'down';
            let tableType = this.closest('table').getAttribute('data-table-type');
            
            fetch(`/admin/${tableType}/${direction}/${id}`, {
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


