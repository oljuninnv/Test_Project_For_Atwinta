import axios from '../../libs/axios';
import { IGetWorkersParams, IGetWorkersResponse } from '../types/worker';

export const GetWorkers = async (page = 1, per_page = 5, name = null, params?: IGetWorkersParams): Promise<IGetWorkersResponse> => {
    const { data } = await axios.get(`/api/workers?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data; 
};