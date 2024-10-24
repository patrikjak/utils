export function button(label: string, type: string|null = null, id: string = null): string {
    type = type ?? 'primary';
    const idAttr = id === null ? '' : ` id="${id}"`;

    return `<button class="pj-btn ${type}"${idAttr}>${label}</button>`;
}