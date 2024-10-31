import axios from '../../libs/axios';
import { IGetWorkersResponse } from '../types/worker_department';

export const GetWorkers = async (id: number, page = 1, per_page = 2, name?: string): Promise<IGetWorkersResponse> => {
    const { data } = await axios.get(`/api/workers_information/${id}?page=${page}&per_page=${per_page}` + (name ? `&name=${name}` : ''));
    
    // Проверяем, что ответ успешный
    if (!data.success) {
        throw new Error('Ошибка при получении данных о работниках');
    }

    console.log(data);    
    return data; // Возвращаем весь ответ, включая department и employees
};