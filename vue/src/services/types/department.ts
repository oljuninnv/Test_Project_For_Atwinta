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

export interface IDepartment {
    name: string;
    id: number;
}

export interface IGetDepartmentsParams {
    id: number;
    name: string;
};

export interface IPositions {
    id: number;
    name: string;
};

export type TDeportamentResponse = {
   positions: IPositions[];
   department_id: number;
   department_name: string;
   employee_count: number;
};

export interface IGetDepartmentsResponse {
    success: boolean;
    response: PaginationData<TDeportamentResponse>;
 }