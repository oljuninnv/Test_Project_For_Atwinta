import axios from '../../libs/axios';
import { IGetTestTaskesStatusParams, IGetTestTaskesStatusResponse, } from '../types/test_taskes_status';

export const GetTaskesStatus = async (page = 1, per_page = 5, name = null, params?: IGetTestTaskesStatusParams): Promise<IGetTestTaskesStatusResponse> => {
    const { data } = await axios.get(`/api/test_taskes_status?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data.response; 
};