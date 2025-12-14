(() => {
  const el = document.getElementById('dlxHeaderLottie');
  if (!el) return;

  const startAt = 10;
  const topAt = 1;
  let attached = false;

  const attach = () => {
    if (attached) return;

    const d = el.dotLottie;

    

    // Fit behavior inside the canvas
    d.setLayout({ fit: 'contain', align: [0.5, 0.5] }); // or 'cover' if you have lots of empty padding
    d.setRenderConfig({ autoResize: true });

    // Compute a proper width from the animation’s native aspect ratio
    const { width, height } = d.animationSize();
    const targetH = 150; // must match your CSS height above
    const targetW = Math.round(targetH * (width / height));

    el.style.height = `${targetH}px`;
    el.style.width = `${targetW}px`;

    // Force the renderer to match the element’s new size
    d.resize();


    if (!d) return; // ainda não está ready

    attached = true;

    d.setLoop(false);
    d.setFrame(0);
    d.pause();

    let state = 'top';

    const onScroll = () => {
      const y = window.scrollY || document.documentElement.scrollTop || 0;

      if (y > startAt && state !== 'down') {
        state = 'down';
        d.setMode('forward');
        d.play();
      } else if (y <= topAt && state !== 'top') {
        state = 'top';
        d.setMode('reverse');
        d.play();
      }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  };

  // Se já estiver pronto, liga já
  if (el.dotLottie) attach();

  // Se ainda não estiver, espera pelo ready
  el.addEventListener('ready', attach, { once: true });

  // fallback (caso ready tenha disparado cedo)
  setTimeout(attach, 250);
  setTimeout(attach, 1000);
})();


(() => {
  const header = document.querySelector('#masthead.site-header.layout--three');
  if (!header) return;

  let ticking = false;
  let collapsed = null;

  const apply = () => {
    ticking = false;

    const y = window.scrollY || document.documentElement.scrollTop || 0;
    const shouldCollapse = y > 1; // collapse whenever you're not at top

    if (shouldCollapse === collapsed) return;
    collapsed = shouldCollapse;

    document.body.classList.toggle('dlx-header-collapsed', shouldCollapse);
  };

  const onScroll = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(apply);
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  apply();
})();


