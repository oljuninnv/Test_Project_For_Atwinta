import axios from '../../libs/axios';
import { ILoginParams, ILoginResponse, IRegisterParams, IRegisterResponse, IResetLinkParams,IResetLinkResponse,IResetPasswordParams, IResetPasswordResponse, IAddUserParams, IAddUserResponse,IGetUsersParams,IGetUsersResponse, IDeleteUserParams, IDeleteUserResponse} from '../types/auth';


export const loginUser = async (params: ILoginParams): Promise<ILoginResponse> => {
    const { data} = await axios.post('/api/auth/login', params);
    return data.response
}

export const registerUser = async (params: IRegisterParams): Promise<IRegisterResponse> => {
    const { data} = await axios.post('/api/auth/register', params);
    return data.response
}

export const ResetLink = async (params: IResetLinkParams): Promise<IResetLinkResponse> => {
    const { data } = await axios.post('/api/auth/restore', params);
    return {
        success: data.success, 
        msg: data.msg,
    };
};

export const ResetPassword = async (params: IResetPasswordParams): Promise<IResetPasswordResponse> => {
    const { data } = await axios.post('/api/auth/restore/confirm', params);
    return data.response
};

export const GetUsers = async (params: IGetUsersParams): Promise<IGetUsersResponse> => {
    const { data } = await axios.get('/api/users');
    console.log(data);    
    return data.response; 
};

export const AddUser = async (params: IAddUserParams): Promise<IAddUserResponse> => {
    console.log('kjhkj');
    
    const { data } = await axios.post('/api/users', params);
    return data.response
};

export const DeleteUser = async (id: number): Promise<IDeleteUserResponse> => {
    const { data } = await axios.delete(`/api/users/${id}`);
    console.log(id);
    return data.response
};

// export const UpdateUser = async (id: number): Promise<IUpdateUserResponse> => { 
//     const { data } = await axios.put(`/api/users/${id}`);
//     return data.response
// };