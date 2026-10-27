import { Controller } from '@hotwired/stimulus';

// Onglets accessibles (page tarifs hiver / été) : clic, flèches du clavier, ancre dans l'URL (/tarifs#hiver)
export default class extends Controller {
    static targets = ['tab', 'panel'];

    connect() {
        this.onHashChange = this.onHashChange.bind(this);
        window.addEventListener('hashchange', this.onHashChange);
        this.onHashChange();
    }

    disconnect() {
        window.removeEventListener('hashchange', this.onHashChange);
    }

    select(event) {
        event.preventDefault();
        this.show(this.tabTargets.indexOf(event.currentTarget));
        history.replaceState(null, '', event.currentTarget.hash);
    }

    keydown(event) {
        const moves = { ArrowLeft: -1, ArrowRight: 1 };
        if (!(event.key in moves)) {
            return;
        }
        event.preventDefault();
        const count = this.tabTargets.length;
        const index = (this.tabTargets.indexOf(event.currentTarget) + moves[event.key] + count) % count;
        this.show(index);
        this.tabTargets[index].focus();
        history.replaceState(null, '', this.tabTargets[index].hash);
    }

    // ancre d'un onglet (#ete) ou d'un élément dans un onglet (#tarifs-12) : ouvre l'onglet qui la contient
    onHashChange() {
        const id = decodeURIComponent(window.location.hash.slice(1));
        const target = id && document.getElementById(id);
        const index = target ? this.panelTargets.findIndex((panel) => panel.contains(target)) : -1;
        if (index === -1) {
            return;
        }
        this.show(index);
        if (target !== this.panelTargets[index]) {
            target.scrollIntoView();
        }
    }

    show(index) {
        if (this.tabTargets.length === 0) {
            return;
        }
        this.tabTargets.forEach((tab, i) => {
            tab.setAttribute('aria-selected', i === index ? 'true' : 'false');
            tab.tabIndex = i === index ? 0 : -1;
        });
        this.panelTargets.forEach((panel, i) => {
            panel.hidden = i !== index;
        });
    }
}
