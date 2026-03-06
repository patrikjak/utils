import {SearchCriteria, TableWrapper} from "../interfaces/table";
import {dispatchUpdateEvent} from "./table";

let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null;

export function bindSearch(tableWrapper: TableWrapper): void {
    const searchInput: HTMLInputElement | null = tableWrapper.querySelector('.table-options .search-wrapper .search-input');

    if (searchInput === null) {
        return;
    }

    searchInput.addEventListener('input', function (): void {
        if (searchDebounceTimer !== null) {
            clearTimeout(searchDebounceTimer);
        }

        searchDebounceTimer = setTimeout((): void => {
            const query: string = searchInput.value.trim();

            dispatchUpdateEvent(tableWrapper, {
                page: 1,
                searchCriteria: <SearchCriteria> {
                    query: query === '' ? null : query,
                    deleteSearch: query === '',
                },
            });
        }, 400);
    });
}

export function getCurrentSearchCriteria(tableWrapper: TableWrapper): SearchCriteria {
    const searchInput: HTMLInputElement | null = tableWrapper.querySelector('.table-options .search-wrapper .search-input');
    const query: string = searchInput !== null ? searchInput.value.trim() : '';

    return {
        query: query === '' ? null : query,
        deleteSearch: query === '',
    };
}
