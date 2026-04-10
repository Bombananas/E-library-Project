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

    goBack() {
        window.history.back();
    }, 
    editMajor(id) {
    loadData('addMajorForm.php?edit_id=' + id);
    fetch('majorList.php?edit_id=' + id + '&ajax=1', { credentials: 'same-origin' })
        .then(res => res.json())
}
};

const loadData = App.loadData;
const loadIntoSubjectSelect = App.loadIntoSubjectSelect;
const deleteMajor = App.deleteMajor;
const editMajor = App.editMajor;
const closeForm = App.closeForm;
const goBack = App.goBack;

