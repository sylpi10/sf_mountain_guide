import { Controller } from '@hotwired/stimulus';

// Message flash qui disparaît tout seul
export default class extends Controller {
    connect() {
        this.timer = setTimeout(() => {
            this.element.classList.add('fade-out');
            this.element.addEventListener('transitionend', () => this.element.remove());
        }, 2600);
    }

    disconnect() {
        clearTimeout(this.timer);
    }
}
