document.addEventListener('DOMContentLoaded', () => {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    $navbarBurgers.forEach((element) => {
        element.addEventListener('click', () => {
            const target = element.dataset.target;
            const $target = document.getElementById(target);

            element.classList.toggle('is-active');
            $target.classList.toggle('is-active');
        });
    });

    const $infoTexts = document.querySelectorAll('.info-text');

    $infoTexts.forEach((infoText) => {
        setTimeout(() => {
            infoText.classList.add('is-hidden');
        }, 3500);
    });

    const activeTab = window.localStorage.getItem('active-tab');
    const actionFreewarTab = document.getElementById('action-freewar-tab');
    const chaosFreewarTab = document.getElementById('chaos-freewar-tab');
    const actionFreewarTabContent = document.getElementById('action-freewar-tab-content');
    const chaosFreewarTabContent = document.getElementById('chaos-freewar-tab-content');

    const activateActionFreewarTab = () => {
        actionFreewarTab.classList.add('is-active');
        chaosFreewarTab.classList.remove('is-active');
        actionFreewarTabContent.classList.remove('is-hidden');
        chaosFreewarTabContent.classList.add('is-hidden');
    }

    const activateChaosFreewarTab = () => {
        chaosFreewarTab.classList.add('is-active');
        actionFreewarTab.classList.remove('is-active');
        chaosFreewarTabContent.classList.remove('is-hidden');
        actionFreewarTabContent.classList.add('is-hidden');
    }

    if (actionFreewarTab !== null && chaosFreewarTab !== null && actionFreewarTabContent !== null && chaosFreewarTabContent !== null) {
        if (activeTab === 'action-freewar') {
            activateActionFreewarTab();
        }

        if (activeTab === 'chaos-freewar') {
            activateChaosFreewarTab();
        }

        actionFreewarTab.addEventListener('click', () => {
            activateActionFreewarTab();
            window.localStorage.setItem('active-tab', 'action-freewar');
        });

        chaosFreewarTab.addEventListener('click', () => {
            activateChaosFreewarTab();
            window.localStorage.setItem('active-tab', 'chaos-freewar');
        });
    }

    const resetActionFreewarForm = document.getElementById('reset-action-freewar-form')

    if (resetActionFreewarForm !== null) {
        resetActionFreewarForm.addEventListener('submit', (event) => {
            event.preventDefault();
        });
    }

    const resetActionFreewarButton = document.getElementById('reset-action-freewar-button');

    if (resetActionFreewarButton !== null) {
        resetActionFreewarButton.addEventListener('click', () => {
            const resetActionFreewarForm = document.getElementById('reset-action-freewar-form');

            if (resetActionFreewarForm !== null) {
                resetActionFreewarForm.submit();
            }
        });
    }

    const openModal = (element) => {
        element.classList.add('is-active');
    }

    const closeModal = (element) => {
        element.classList.remove('is-active');
    }

    const closeAllModals = () => {
        (document.querySelectorAll('.modal') || []).forEach((modal) => {
            closeModal(modal);
        });
    }

    (document.querySelectorAll('.js-modal-trigger') || []).forEach((trigger) => {
        const modal = trigger.dataset.target;
        const target = document.getElementById(modal);

        trigger.addEventListener('click', () => {
            openModal(target);
        });
    });

    (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach((close) => {
        const target = close.closest('.modal');

        close.addEventListener('click', () => {
            closeModal(target);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllModals();
        }
    });
});
