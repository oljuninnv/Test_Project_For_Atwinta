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

export interface IWorker {
    adopted_at: string; 
    name: string;
    image: string;
    position: string;
    id: number;
}

export type TWorkerResponse = {
    department_id: number;
    department_name: string;
    workers: IWorker[]; // Это массив работников
};

export interface IGetWorkersResponse {
    success: boolean;
    response: PaginationData<TWorkerResponse>; // Здесь мы используем PaginationData с TWorkerResponse
}