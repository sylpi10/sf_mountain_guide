import { Controller } from '@hotwired/stimulus';

// Slider horizontal (avis clients) : défilement natif (scroll-snap, swipe), boutons précédent / suivant,
// textes longs repliés avec « Lire la suite »
export default class extends Controller {
    static targets = ['track', 'item', 'prev', 'next', 'text'];

    connect() {
        this.onResize = this.onResize.bind(this);
        window.addEventListener('resize', this.onResize, { passive: true });
        this.onResize();
    }

    disconnect() {
        window.removeEventListener('resize', this.onResize);
    }

    prev() {
        this.scrollBy(-1);
    }

    next() {
        this.scrollBy(1);
    }

    // décale d'autant de cartes qu'il y en a de visibles
    scrollBy(direction) {
        const item = this.itemTargets[0];
        if (!item) {
            return;
        }
        const gap = parseFloat(getComputedStyle(this.trackTarget).columnGap) || 0;
        const step = item.offsetWidth + gap;
        const visible = Math.max(1, Math.floor((this.trackTarget.clientWidth + gap) / step));
        this.trackTarget.scrollBy({ left: direction * visible * step, behavior: 'smooth' });
    }

    // boutons désactivés en bout de piste, masqués si tout tient à l'écran
    update() {
        const { scrollLeft, scrollWidth, clientWidth } = this.trackTarget;
        const scrollable = scrollWidth > clientWidth + 1;
        this.prevTarget.hidden = this.nextTarget.hidden = !scrollable;
        // marge de quelques px : le scroll-snap peut s'arrêter juste avant le bord
        this.prevTarget.disabled = scrollLeft <= 8;
        this.nextTarget.disabled = scrollLeft + clientWidth >= scrollWidth - 8;
    }

    toggle(event) {
        const button = event.currentTarget;
        const open = button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', open);
        button.closest('[data-carousel-target="item"]').classList.toggle('is-open', open);
    }

    // « Lire la suite » seulement pour les textes coupés
    onResize() {
        this.textTargets.forEach((text) => {
            const item = text.closest('[data-carousel-target="item"]');
            if (item.classList.contains('is-open')) {
                return;
            }
            text.nextElementSibling.hidden = text.scrollHeight <= text.clientHeight + 1;
        });
        this.update();
    }
}
