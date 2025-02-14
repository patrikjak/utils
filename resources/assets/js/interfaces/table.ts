export type SortCriteria = {
    column: string | null;
    order: string;
};

export type PageCriteria = {
    page: number;
    pageSize: number;
}

export type TableParts = {
    body: string;
    head: string;
    pagination: string | null;
    options: string | null;
}

export interface TableWrapper extends HTMLElement {
    dataset: {
        "html-parts-url": string;
    };
}