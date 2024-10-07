import axios from '../../libs/axios';
import { ILoginParams, ILoginResponse, IRegisterParams, IRegisterResponse, } from '../types/auth';


export const loginUser = async (params: ILoginParams): Promise<ILoginResponse> => {
    const { data} = await axios.post('/api/auth/login', params);
    return data.response
}

export const registerUser = async (params: IRegisterParams): Promise<IRegisterResponse> => {
    const { data} = await axios.post('/api/auth/register', params);
    return data.response
}