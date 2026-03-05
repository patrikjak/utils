import {Filter} from "../interfaces/table";

const TTL_MS: number = 365 * 24 * 60 * 60 * 1000;

export type TableStorageData = {
    page: number;
    pageSize: number;
    sortColumn: string | null;
    sortOrder: string | null;
    filters: Filter[] | null;
};

type StorageEntry = {
    data: TableStorageData;
    expiresAt: number;
};

export function saveTableState(tableId: string, data: TableStorageData): void {
    const entry: StorageEntry = {
        data,
        expiresAt: Date.now() + TTL_MS,
    };

    localStorage.setItem(tableId, JSON.stringify(entry));
}

export function loadTableState(tableId: string): TableStorageData | null {
    const raw: string | null = localStorage.getItem(tableId);

    if (raw === null) {
        return null;
    }

    let entry: StorageEntry;

    try {
        entry = JSON.parse(raw) as StorageEntry;
    } catch {
        localStorage.removeItem(tableId);
        return null;
    }

    if (Date.now() > entry.expiresAt) {
        localStorage.removeItem(tableId);
        return null;
    }

    return entry.data;
}
