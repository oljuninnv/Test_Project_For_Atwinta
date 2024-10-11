import axios from '../../libs/axios';
import { ILoginParams, ILoginResponse, IRegisterParams, IRegisterResponse, IResetLinkParams,IResetLinkResponse,IResetPasswordParams, IResetPasswordResponse} from '../types/auth';


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