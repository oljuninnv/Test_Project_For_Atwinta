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
    id: number;
}

export interface IGetWorkersParams {
    adopted_at: string;
    id: number;
};

export interface IPositions {
    id: number;
    name: string;
};

export interface IDepartments {
    id: number;
    name: string;
};

export interface IUsers {
    id: number;
    name: string;
    login: string;
    email: string;
    image: string;
    about: string;
    type: string;
    github: string;
    city: string;
    is_finished: number;
    phone: string;
    birthday: string;
    created_at: string,
    updated_at: string,
    worker_id: number
};

export type TWorkerResponse = {
    id: number;
   position: IPositions;
   user: IUsers;
   department:IDepartments;
   adopted_at: string;
};

export interface IGetWorkersResponse {
    success: boolean;
    response: PaginationData<TWorkerResponse>;
 }