import './bootstrap';
import * as Turbo from "@hotwired/turbo"

Turbo.start()

// Re-initialize Feather Icons on Turbo navigation
document.addEventListener("turbo:load", () => {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
