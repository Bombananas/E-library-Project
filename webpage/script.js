const App = {
    preventDefault(e) {
    e.preventDefault();},
    preventKeys(e) {
    if (['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End'].includes(e.key)) {
        e.preventDefault();
    }
},
    disableInteraction() {
        const el = document.querySelector('.mainBody');
        if (el) el.classList.add('disableInteraction');
    }
    
,
    reenableInteraction() {
        const el = document.querySelector('.mainBody');
        if (el) el.classList.remove('disableInteraction');
    },

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
        window.addEventListener('wheel', App.preventDefault, { passive: false });
        window.addEventListener('touchmove', App.preventDefault, { passive: false });
        window.addEventListener('keydown', App.preventKeys, false);
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
        App.reenableInteraction();
        window.removeEventListener('wheel', App.preventDefault);
        window.removeEventListener('touchmove', App.preventDefault);
        window.removeEventListener('keydown', App.preventKeys);
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
        disableInteraction();
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
},
passwordComfire() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }
    return true;
},
};
const imagesArray = ['image/595674424_1152675927068072_2433275573075965072_n.jpg', 'image/595070714_1152675970401401_3052436451514776772_n.jpg', 'image/595070714_1152675970401401_3052436451514776772_n.jpg'];
let   currentImageIndex = 1;
function changeBackgroundImage() {
    document.querySelector('.bannerContainer').style.background = `url(${imagesArray[currentImageIndex]})no-repeat center /cover`;
    document.querySelector('.bannerContainer').style.transition = 'background 1s ease-in-out';
    currentImageIndex = (currentImageIndex + 1) % imagesArray.length;
}
const statusMessageElement = document.getElementById('statusMessage');

if (statusMessageElement && !statusMessageElement.hidden) {
  setTimeout(() => {
    statusMessageElement.hidden = true;
  }, 3000);
}
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
const disableInteraction = App.disableInteraction;
const reenableInteraction = App.reenableInteraction;
const passwordComfire = App.passwordComfire;
const statusMessage = App.statusMessage;
setInterval(changeBackgroundImage, 3500);

