import axios from '../../libs/axios';
import { IGetDepartmentsParams, IGetDepartmentsResponse, } from '../types/department';

export const GetDepartments = async (page = 1, per_page = 5, name = null, params?: IGetDepartmentsParams): Promise<IGetDepartmentsResponse> => {
    const { data } = await axios.get(`/api/departments_information?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data; 
};