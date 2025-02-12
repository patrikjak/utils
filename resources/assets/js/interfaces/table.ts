export type SortCriteria = {
    column: string;
    direction: string;
};

export type PageCriteria = {
    page: number;
    pageSize: number;
}

export type TableParts = {
    body: string;
    head: string;
    pagination: string;
}

export interface TableWrapper extends HTMLElement {
    dataset: {
        "html-parts-url": string;
    };
}