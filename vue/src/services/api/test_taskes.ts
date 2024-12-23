import axios from '../../libs/axios';
import { IGetTestTaskesParams, IGetTestTaskesResponse, } from '../types/test_taskes';

export const GetTaskes = async (page = 1, per_page = 5, name = null, params?: IGetTestTaskesParams): Promise<IGetTestTaskesResponse> => {
    const { data } = await axios.get(`/api/test_taskes?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data.response; 
};