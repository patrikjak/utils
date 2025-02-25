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
}

export interface TextFilter extends Filter {
    type: 'text';
    filterType: 'contains' | 'not_contains' |'equals' | 'not_equals' | 'starts_with' | 'ends_with';
    value: string;
}

export interface NumberFilter extends Filter {
    type: 'number';
    from: number;
    to: number;
}

export interface DateFilter extends Filter {
    type: 'date';
    from: string | null;
    to: string | null;
}

export interface SelectFilter extends Filter {
    type: 'select';
    value: string;
}