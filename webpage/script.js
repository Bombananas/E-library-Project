const App = {
    showResult(selector, html) {
        const el = document.querySelector(selector);
        if (el) el.innerHTML = html;
    },
    openUrl(url, onSuccess) {
        return fetch(url, { credentials: 'same-origin' })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.text();
            })
            .then(onSuccess)
            .catch(error => console.error('Fetch error:', error));
    },

    loadData(url) {
        return App.openUrl(url, html => App.showResult('#showResult', html));
    },

    loadIntoSubjectSelect(url) {
        App.openUrl(url, html => App.showResult('.subjectSelect', html));
        App.logLevelIdPre(url);
    },

    logLevelIdPre(url) {
        try {
            const levelIdPre = new URL(url, window.location.origin).searchParams.get('level_id_pre')
                || new URL(url, window.location.origin).searchParams.get('level_idpre');
        } catch (err) {
            console.log('Pre-selected Level ID: none');
        }
    },

    deleteMajor(id, levelId) {
        if (!confirm('Are you sure?')) return;
        fetch(`majorList.php?delete=${id}&ajax=1`, { credentials: 'same-origin' })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    App.loadIntoSubjectSelect(`majorList.php?level_id=${levelId}`);
                } else {
                    alert('Delete failed.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error deleting.');
            });
    },


    closeForm() {
        App.showResult('#showResult', '');
    },

    selectMajor(majorId) {
        window.selectedMajorId = majorId;
        const fullListBtn = document.getElementById('fullListBtn');
        if (window.userRole && fullListBtn) fullListBtn.style.display = 'block';
        App.openUrl('bookList.php?major_id=' + majorId, html => {
            const contentDisplay = document.querySelector('.contentDisplay');
            if (contentDisplay) {
                const oldBookListContainer = contentDisplay.querySelector('.bookListContainer');
                if (oldBookListContainer) {
                    oldBookListContainer.remove();
                }
                contentDisplay.insertAdjacentHTML('beforeend', html);
            }
        });
    },

    loadAddBookFormModal() {
        App.loadData('addBookForm.php').then(() => {
            setTimeout(() => {
                const majorIdInput = document.getElementById('majorId');
                const modal = document.getElementById('bookFormModal');
                if (majorIdInput && window.selectedMajorId) majorIdInput.value = window.selectedMajorId;
                if (modal) modal.classList.add('show');
            }, 100);
        });
    },

    editBook(bookId) {
        App.loadData('addBookForm.php?edit_id=' + bookId).then(() => {
            setTimeout(() => {
                const modal = document.getElementById('bookFormModal');
                if (modal) modal.classList.add('show');
            }, 100);
        });
    },

    goBack() {
        window.history.back();
    }, 
    editMajor(id) {
    loadData('addMajorForm.php?edit_id=' + id);
    fetch('majorList.php?edit_id=' + id + '&ajax=1', { credentials: 'same-origin' })
        .then(res => res.json())
},
addClass(className, element) {
    document.querySelectorAll('.levelLink').forEach(btn => btn.classList.remove('activateLevelLink'));
    const elements = document.getElementById(element);
    if (elements) {
        elements.classList.add(className);
        document.querySelector('.subjectSelect').classList.add('displayMajorList');
    }
}
};

const loadData = App.loadData;
const loadIntoSubjectSelect = App.loadIntoSubjectSelect;
const deleteMajor = App.deleteMajor;
const editMajor = App.editMajor;
const closeForm = App.closeForm;
const selectMajor = App.selectMajor;
const loadAddBookFormModal = App.loadAddBookFormModal;
const editBook = App.editBook;
const goBack = App.goBack;
const addClass = App.addClass;
