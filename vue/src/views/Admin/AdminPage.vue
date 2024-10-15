<template>
   <div class="flex flex-wrap bg-gray-100 w-full h-screen">
       <div class="bg-white rounded p-3 shadow-lg w-full md:w-3/12 lg:w-2/12">
           <div class="flex items-center space-x-4 p-2 mb-5">
               <div>
                   <h4 class="font-semibold text-lg text-gray-700 capitalize font-poppins tracking-wide">AdminPanel</h4>
               </div>
           </div>
           <ul class="space-y-2 text-sm">
               <li>
                   <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                       <span>Пользователи</span>
                   </a>
               </li>
               <li>
                   <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                       <span>Сотрудники</span>
                   </a>
               </li>
               <li>
                   <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                       <span>Отделы</span>
                   </a>
               </li>
               <li>
                   <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                       <span class="text-gray-600">
                           <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                           </svg>
                       </span>
                       <span>Выйти</span>
                   </a>
               </li>
           </ul>
       </div>

       <div class="w-full md:w-9/12 lg:w-10/12 p-4">
         <div class="text-gray-500">
               <!-- <AdminUserTable></AdminUserTable>  -->
                <!-- <AdminWorkerTable></AdminWorkerTable>  -->
                <!-- <AdminDepartmentTable></AdminDepartmentTable> -->
                 <!-- <AdminAddUser></AdminAddUser> -->
                  <!-- <AdminAddWorker></AdminAddWorker>  -->
                   <AdminAddDepartment></AdminAddDepartment>
           </div>
       </div>
   </div>
</template>

<script setup>
    import AdminUserTable from "../../components/AdminUserTable.vue"
    import AdminDepartmentTable from "../../components/AdminDepartmentTable.vue";
    import AdminWorkerTable from "../../components/AdminWorkerTable.vue"
    import AdminNav from "../../components/AdminWorkerTable.vue"
    import AdminAddUser from "../../components/AdminAddUser.vue";
    import AdminAddWorker from "../../components/AdminAddWorker.vue";
    import AdminAddDepartment from "../../components/AdminAddDepartment.vue";
</script>
  
 