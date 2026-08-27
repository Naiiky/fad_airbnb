import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['fileInput', 'input', 'item', 'list', 'preview'];

    previewUploads() {
        if (!this.hasPreviewTarget || !this.hasFileInputTarget) {
            return;
        }

        this.previewTarget.innerHTML = '';

        Array.from(this.fileInputTarget.files).forEach((file) => {
            if (!file.type.startsWith('image/')) {
                return;
            }

            const card = document.createElement('div');
            card.className = 'overflow-hidden rounded-[10px] border border-[#154539]/10 bg-[#fcf9f4]';

            const frame = document.createElement('div');
            frame.className = 'aspect-[4/3] bg-[#e8eee9]';

            const image = document.createElement('img');
            image.className = 'h-full w-full object-cover';
            image.alt = file.name;
            image.src = URL.createObjectURL(file);
            image.addEventListener('load', () => URL.revokeObjectURL(image.src), { once: true });

            const label = document.createElement('p');
            label.className = 'truncate px-3 py-2 text-xs font-medium text-[#404945]';
            label.textContent = file.name;

            frame.appendChild(image);
            card.appendChild(frame);
            card.appendChild(label);
            this.previewTarget.appendChild(card);
        });
    }

    moveUp(event) {
        const item = event.currentTarget.closest('[data-property-images-target="item"]');
        const previous = item?.previousElementSibling;

        if (item && previous) {
            this.listTarget.insertBefore(item, previous);
            this.refreshInputs();
        }
    }

    moveDown(event) {
        const item = event.currentTarget.closest('[data-property-images-target="item"]');
        const next = item?.nextElementSibling;

        if (item && next) {
            this.listTarget.insertBefore(next, item);
            this.refreshInputs();
        }
    }

    refreshInputs() {
        this.itemTargets.forEach((item) => {
            const input = item.querySelector('input[data-property-images-target="input"]');
            if (input) {
                item.appendChild(input);
            }
        });
    }
}
