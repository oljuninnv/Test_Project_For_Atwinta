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
    about: string;
    image: string;
    id: number;
}

export interface ILoginResponse {
    data: IUser;
    token: string;
}

export interface IResetLinkParams {
    email: string;
}

export interface IResetLinkResponse {
    success: boolean;
    msg: string;
}

export interface IResetPasswordParams {
    token: string,
    password: string,
    password_confirmation: string
}

export interface IResetPasswordResponse {
    success: boolean,
    msg: string
}

export interface IAddUserParams {
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    github: string;
    type: string;
    about: string;
    image: string;
    password: string;
};

export interface IAddUserResponse {
    data: IUser;
    msg: string;
}

export interface IGetUsersParams {
    id: number;
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    github: string;
    type: string;
    about: string;
    image: string;
    password: string;
};

export interface IGetUsersResponse {
    data: IUser [];
    msg: string;
}

export interface IDeleteUserParams {
    id: number; 
}

export interface IDeleteUserResponse {
    msg: string; // Сообщение об успешном удалении
}

export interface IUpdateParams {
    id: number; // Добавим поле id для идентификации пользователя
    login: string; // Поля могут быть необязательными
    name: string;
    phone: string;
    city: string;
    birthday: string;
    github: string;
    email: string;
    type: string;
    image: string; // Поле для обновления изображения
    about: string; // Дополнительное поле о пользователе
};

export interface IUpdateResponse {
    data: IUser; // Ответ будет содержать обновленного пользователя
}