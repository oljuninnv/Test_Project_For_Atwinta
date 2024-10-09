export interface ILoginParams {
    email: string;
    password: string;
};

export interface IRegisterParams {
    login: string;
    name: string;
    phone: string;
    city: string;
    birthday: string;
    github: string;
    email: string;
    type: string;
    password: string;
};

export interface IRegisterResponse {
    data: IUser;
    token: string;
}

export interface IUser {
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    github: string;
    type: string;
    id: number;
}

export interface ILoginResponse {
    data: IUser;
    token: string;
}