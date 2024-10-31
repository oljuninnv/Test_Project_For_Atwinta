import axios from '../../libs/axios';
import { IGetWorkersResponse } from '../types/worker_department';

export const GetWorkers = async (id:number, page = 1, per_page = 5, name?:string): Promise<IGetWorkersResponse> => {
    const { data } = await axios.get(`/api/workers_information/${id}?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data.response; 
};