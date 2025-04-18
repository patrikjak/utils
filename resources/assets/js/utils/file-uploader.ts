import {createElement, getData, showElement} from "../helpers/general";
import {filePreview} from "../components/file-preview";

export function bindUploaders(): void {
    const fileUploaders: NodeListOf<HTMLElement> = document.querySelectorAll('.pj-photo-uploader');

    fileUploaders.forEach((uploader: HTMLElement): void => {
        bindUploader(uploader);

        const previews: NodeListOf<HTMLElement> = uploader.querySelectorAll('.previews .pj-photo-preview');

        previews.forEach((preview: HTMLElement): void => {
            const image: HTMLImageElement = preview.querySelector('img');
            const fileName: string = getData(image, 'file-name');

            bindDelete(preview, uploader, fileName);
        });
    });
}

export function bindUploader(uploader: HTMLElement): void {
    const fileInput: HTMLInputElement = uploader.querySelector('input[type="file"]');
    const previews: HTMLElement = uploader.querySelector('.previews');

    fileInput.addEventListener('change', (event: Event): void => {
        const files: FileList = (event.target as HTMLInputElement).files;

        // @ts-ignore
        for (const file of files) {
            if (!isImage(file)) {
                alert(`Invalid file type: ${file.type}`);
                continue;
            }

            const filePreviewElement: HTMLElement = filePreview(createElement('img', null, {
                src: URL.createObjectURL(file),
                alt: file.name,
            }));

            showElement(filePreviewElement, previews);
            bindDelete(filePreviewElement, uploader, file);
        }
    });
}

function bindDelete(filePreview: HTMLElement, uploader: HTMLElement, file: File|string): void {
    const deleteButton: HTMLElement = filePreview.querySelector('.delete-button');
    const fileInput: HTMLInputElement = uploader.querySelector('input[type="file"]');

    deleteButton.addEventListener('click', (): void => {
        const preview: HTMLElement = filePreview.closest('.pj-photo-preview');
        preview.remove();

        const fileName: string = typeof file === 'string' ? file : file.name;

        const deletedFileInput = createElement('input', null, {
            type: 'hidden',
            name: `deleted_files_${fileInput.name}[]`,
            value: fileName,
        });

        uploader.appendChild(deletedFileInput);
    });
}

function isImage(file: File): boolean {
    const fileTypes: string[] = [
        "image/apng",
        "image/bmp",
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/svg+xml",
        "image/tiff",
        "image/webp",
        "image/x-icon",
    ];

    return fileTypes.includes(file.type);
}