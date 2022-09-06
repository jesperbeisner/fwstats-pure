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

    const actionFreewarTab = document.getElementById('action-freewar-tab');
    const chaosFreewarTab = document.getElementById('chaos-freewar-tab');
    const actionFreewarTabContent = document.getElementById('action-freewar-tab-content');
    const chaosFreewarTabContent = document.getElementById('chaos-freewar-tab-content');

    if (
      actionFreewarTab !== null
      && chaosFreewarTab !== null
      && actionFreewarTabContent !== null
      && chaosFreewarTabContent !== null
    ) {
        actionFreewarTab.addEventListener('click', () => {
            actionFreewarTab.classList.add('is-active');
            chaosFreewarTab.classList.remove('is-active');

            actionFreewarTabContent.classList.remove('is-hidden');
            chaosFreewarTabContent.classList.add('is-hidden');
        });

        chaosFreewarTab.addEventListener('click', () => {
            chaosFreewarTab.classList.add('is-active');
            actionFreewarTab.classList.remove('is-active');

            chaosFreewarTabContent.classList.remove('is-hidden');
            actionFreewarTabContent.classList.add('is-hidden');
        });
    }

    const $infoTexts = document.querySelectorAll('.info-text');
    $infoTexts.forEach((infoText) => {
        setTimeout(() => {
            infoText.classList.add('is-hidden');
        }, 2500);
    });
});
