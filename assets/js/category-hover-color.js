(() => {
  const isTransparent = (color) => {
    if (!color) return true;
    if (color === 'transparent') return true;

    const match = color.match(/rgba?\(([^)]+)\)/);
    if (!match) return false;

    const parts = match[1].split(',').map((value) => value.trim());
    if (parts.length === 4) {
      const alpha = parseFloat(parts[3]);
      return Number.isNaN(alpha) ? false : alpha === 0;
    }

    return false;
  };

  const applyCategoryHoverColors = () => {
    const lists = document.querySelectorAll('.post-categories');
    if (!lists.length) return;

    lists.forEach((list) => {
      const catItem = list.querySelector('.cat-item');
      if (!catItem) return;

      let container = list.closest('.blaze_box_wrap, article, .post-item, .list-item');
      const fullImageBanner = list.closest('.top-main-banner-item');
      if (fullImageBanner) {
        container = list.closest('.dn-narrow-wrap') || list.closest('.post-element');
      }
      if (!container) return;
      if (container.dataset.dlxCategoryHover === '1') return;

      let color = window.getComputedStyle(catItem).backgroundColor;
      if (isTransparent(color)) {
        const link = catItem.querySelector('a');
        if (link) color = window.getComputedStyle(link).color;
      }

      if (isTransparent(color)) return;

      container.style.setProperty('--dlx-category-color', color);
      container.classList.add('dlx-category-hover');
      container.dataset.dlxCategoryHover = '1';
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyCategoryHoverColors);
  } else {
    applyCategoryHoverColors();
  }
})();
