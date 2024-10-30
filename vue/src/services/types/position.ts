export type PaginationData<T> = {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string;
        label: string;
        active: boolean;
    }[];
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}

export interface IPosition {
    name: string;
    id: number;
}

export interface IGetPositionsParams {
    id: number;
    name: string;
};

export interface IGetPositionsResponse {
    success: boolean;
    response: PaginationData<IPosition>;
 }