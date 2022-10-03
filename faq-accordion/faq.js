const accordionItems = document.querySelectorAll('.js-accordion-item');

if (accordionItems[0] !== null && accordionItems[0] !== undefined) {
  const accordionContents = document.querySelectorAll('.js-accordion-content');
  const accordionButtons = document.querySelectorAll('.js-accordion-button');
  let timeout;

  // Get accordion content height and hide content
  for (const content of accordionContents) {
    let realHeight = content.offsetHeight;
    content.setAttribute('data-height', `${realHeight}px`);

    if (!content.classList.contains('is-toggled')) content.style.height = 0;
  }

  const openItem = (button, content) => {
    button.classList.add('is-toggled');
    button.setAttribute('aria-expanded', 'true');
    content.style.height = content.getAttribute('data-height');
    content.removeAttribute('aria-hidden');
    content.classList.add('is-toggled');
  };

  const closeItem = (button, content) => {
    button.classList.remove('is-toggled');
    button.setAttribute('aria-expanded', 'false');
    content.style.height = 0;
    content.setAttribute('aria-hidden', 'true');
    content.classList.remove('is-toggled');
  };

  const calculateContentHeight = () => {
    for (const content of accordionContents) {
      content.style.height = 'auto';
      let realHeight = content.offsetHeight;

      content.setAttribute('data-height', `${realHeight}px`);
      content.style.height = content.classList.contains('is-toggled') ? realHeight : 0;
    }
  };

  // Loop accordion buttons and create click events for toggling content
  for (const button of accordionButtons) {
    button.addEventListener('click', (e) => {
      const button = e.currentTarget;
      const target = button.getAttribute('aria-controls');
      const targetEl = document.querySelector(`#${target}`);

      if (button.classList.contains('is-toggled')) {
        closeItem(button, targetEl);
      } else {
        openItem(button, targetEl);
      }
    });
  }

  // After window resize, re-calculate accordion content size
  window.addEventListener('resize', (e) => {
    clearTimeout(timeout);
    timeout = setTimeout(calculateContentHeight, 500);
  });
}