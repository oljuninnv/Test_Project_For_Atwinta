import axios from '../../libs/axios';
import { IGetPositionsParams, IGetPositionsResponse, } from '../types/position';

export const GetPositions = async (page = 1, per_page = 5, name = null, params?: IGetPositionsParams): Promise<IGetPositionsResponse> => {
    const { data } = await axios.get(`/api/positions?page=${page}&per_page=${per_page}`  + (name ? `&name=${name}` : ''));
    console.log(data);    
    return data; 
};