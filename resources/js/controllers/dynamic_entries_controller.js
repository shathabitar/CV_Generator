import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["entry", "addBtn"]
    static values = { type: String }

    connect() {
        this.ensureIndexes()
    }

    add(event) {
        event?.preventDefault()
        const type = this.typeValue || this.data.get('type')
        if (!type) return

        const lastEntry = this.entryTargets[this.entryTargets.length - 1]
        const index = lastEntry ? (parseInt(lastEntry.dataset.index, 10) + 1) : 0
        const url = `/cv/create/entry-template?type=${encodeURIComponent(type)}&index=${index}`
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.text())
          .then(html => {
              this.element.insertAdjacentHTML('beforeend', html)
              this.ensureIndexes()
          })
          .catch(() => alert('Failed to add a new entry.'))
    }

    remove(event) {
        event?.preventDefault()
        const entry = event.currentTarget.closest('[data-dynamic-entries-target="entry"]')
        if (entry) {
            entry.remove()
            this.ensureIndexes()
        }
    }

    ensureIndexes() {
        // Reindex inputs to keep sequential numeric indexes for arrays
        this.entryTargets.forEach((entryEl, i) => {
            entryEl.dataset.index = String(i)
            const type = entryEl.dataset.type
            const inputs = entryEl.querySelectorAll('input, textarea, select')
            inputs.forEach(input => {
                const name = input.getAttribute('name') || ''
                // Replace the numeric segment with current index for names like education[0][degree]
                const newName = name.replace(/(\w+)\[\d+\]/, `$1[${i}]`)
                input.setAttribute('name', newName)
            })
        })
    }
}