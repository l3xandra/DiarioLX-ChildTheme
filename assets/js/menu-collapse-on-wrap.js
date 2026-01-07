(function () {
  var masthead = document.getElementById('masthead');
  if (!masthead || !masthead.classList.contains('layout--three')) {
    return;
  }

  var nav = document.getElementById('site-navigation');
  if (!nav) {
    return;
  }

  var menu = nav.querySelector('ul.menu, ul.nav-menu');
  if (!menu) {
    return;
  }

  var className = 'dlx-menu-collapsed';

  function isWrapped() {
    var items = menu.children;
    if (!items.length) {
      return false;
    }

    var firstTop = items[0].offsetTop;
    for (var i = 1; i < items.length; i += 1) {
      if (items[i].offsetTop - firstTop > 1) {
        return true;
      }
    }

    return false;
  }

  function update() {
    var hadClass = nav.classList.contains(className);
    if (hadClass) {
      nav.classList.remove(className);
    }

    nav.classList.toggle(className, isWrapped());
  }

  var scheduled = false;
  function schedule() {
    if (scheduled) {
      return;
    }
    scheduled = true;
    window.requestAnimationFrame(function () {
      scheduled = false;
      update();
    });
  }

  window.addEventListener('resize', schedule);
  window.addEventListener('orientationchange', schedule);
  window.addEventListener('load', update);
  update();
})();
