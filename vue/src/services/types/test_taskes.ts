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

export interface ITestTaskes {
    time_limit_in_weeks: number;
    name: string;
    file: string;
    id: number;
}

export interface IGetTestTaskesParams {
    id: number;
    name: string;
    file: string;
    time_limit_in_weeks: number;
};


export type TTestTaskesResponse = {
   testTaskes_id: number;
   testTaskes_name: string;
   testTaskesFile: number;
   time_limit_in_weeks: number;
};

export interface IGetTestTaskesResponse {
    success: boolean;
    response: PaginationData<TTestTaskesResponse>;
 }