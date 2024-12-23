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

export interface ITestTaskesStatus {
    status: string;
    start_date: string;
    end_date: string;
    task_id: number;
    user_id: number;
    id: number;
}

export interface IGetTestTaskesStatusParams {
    status: string;
    start_date: string;
    end_date: string;
    task_id: number;
    user_id: number;
    id: number;
};


export type TTestTaskesStatusResponse = {
    status: string;
    start_date: string;
    end_date: string;
    task_id: number;
    user_id: number;
    id: number;
};

export interface IGetTestTaskesStatusResponse {
    success: boolean;
    response: PaginationData<TTestTaskesStatusResponse>;
 }