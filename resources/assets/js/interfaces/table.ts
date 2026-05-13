export type SortCriteria = {
    column: string | null;
    order: string;
    deleteSort: boolean;
};

export type PageCriteria = {
    page: number;
    pageSize: number;
}

export type FilterCriteria = {
    filters: Filter [] | null,
    deleteFilters: boolean;
};

export type SearchCriteria = {
    query: string | null;
    deleteSearch: boolean;
};

export type ColumnsCriteria = {
    visibleColumns: string[] | null;
};

export type TableParts = {
    body: string;
    head: string;
    pagination: string | null;
    options: string | null;
}

export type FilterModalResponse = {
    modal: string;
}

export interface TableWrapper extends HTMLElement {
    dataset: {
        "html-parts-url": string;
    };
}

export interface Filter {
    column: string;
    type: string;
    data: Record<string, string | number | null>;
}
