export type PaginationData<T> = {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null; // URL может быть null для предыдущей или следующей страницы
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null; // URL может быть null
    path: string;
    per_page: number;
    prev_page_url: string | null; // URL может быть null
    to: number;
    total: number;
}

export interface IWorker {
    adopted_at: string; 
    name: string;
    image: string;
    position: string;
    worker_id: number;
    id: number;
}

export interface IDepartment {
    id: number;
    name: string;
}

export type TWorkerResponse = {
    department: IDepartment; // Объект отдела
    employees: IWorker[]; // Это массив работников
};

export interface IGetWorkersResponse {
    success: boolean;
    response: PaginationData<IWorker>; // Здесь мы используем PaginationData с IWorker
}